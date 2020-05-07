<?php

namespace Client\Controllers;

use Carbon\Carbon;
use Client\Controllers\Shared\SiteController;
use Shared\Core\Controllers\IProtected;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Shared\Core\Validation\Validator;
use Client\Repositories\ChatRepository;
use Client\Repositories\MessageRepository;
use Client\Repositories\UserRepository\UserRepository;
use Client\Services\AttachmentService;
use Client\Services\AuthService;

class ChatController extends SiteController implements IProtected
{
    private const MESSAGES_LIMIT_PER_REQUEST = 20;

    public function getProtectedMethods()
    {
        return ['all', 'concrete', 'getMessages', 'saveAttachment'];
    }

    public function all(Request $request)
    {
        /** @var AuthService $authService */
        $authService = App::get('authService');
        $user = $authService->getUser();

        /** @var ChatRepository $chatRepository */
        $chatRepository = App::get('chat');
        $chats = $chatRepository->getChatsByUserId($user['id']);

        return $this->render(['chats' => $chats]);
    }

    public function concrete(Request $request, $userId)
    {
        /** @var AuthService $authService */
        $authService = App::get('authService');
        $me = $authService->getUser();

        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $receiver = $userRepository->getById($userId);

        /** @var ChatRepository $chatRepository */
        $chatRepository = App::get('chat');
        $chatId = $chatRepository->getChatIdByUsers([$me['id'], $receiver['id']]);

        /** @var ChatRepository $chatRepository */
        $chatRepository = App::get('chat');
        $chats = $chatRepository->getChatsByUserId($me['id']);

        $isNewChat = false;

        if ($chatId !== null) {
            /** @var MessageRepository $messageRepository */
            $messageRepository = App::get('message');
            $messages = $messageRepository->getMessagesByChatId($chatId, self::MESSAGES_LIMIT_PER_REQUEST);

            foreach ($messages as &$message) {
                $message['createdAt'] = Carbon::parse($message['createdAt'])->locale('ru')
                    ->isoFormat('D MMMM, HH:mm');
            }

            $messageRepository->setAllMessagesWasRead($chatId, $me['id']);
            $messagesCount = $messageRepository->getAllMessagesCount($chatId);
        } else {
            $isNewChat = true;
            $messagesCount = 0;
            $messages = [];
            array_unshift($chats, [
                'userId' => $receiver['id'],
                'chatId' => null,
                'name' => $receiver['name'],
                'age' => $receiver['age'],
                'lastConnected' => $receiver['lastConnected'],
                'isConnected' => $receiver['isConnected'],
                'clientPath' => $receiver['clientPath'],
            ]);
        }

        return $this->render([
            'chats'         => $chats,
            'receiver'      => $receiver,
            'messages'      => $messages,
            'messagesCount' => $messagesCount,
            'isNewChat'     => $isNewChat,
        ]);
    }

    public function getMessages(Request $request, $chatId)
    {
        /** @var Validator $validator */
        $validator = App::get('validator', $request->get(), [
            'offset' => 'required'
        ]);

        if (!$validator->isValid()) {
            $this->renderJson([
                'data' => [],
                'error' => true,
                'errorText' => 'Argument offset not valid',
            ]);
        }

        /** @var MessageRepository $messageRepository */
        $messageRepository = App::get('message');
        $messages = $messageRepository->getMessagesByChatId(
            $chatId, self::MESSAGES_LIMIT_PER_REQUEST, $request->get('offset')
        );

        foreach ($messages as &$message) {
            $message['createdAt'] = Carbon::parse($message['createdAt'])->locale('ru')
                ->isoFormat('D MMMM, HH:mm');
        }

        $this->renderJson([
            'data' => [
                'messages' => array_reverse($messages),
            ],
            'error' => false,
        ]);
    }

    public function saveAttachment(Request $request)
    {
        $chatId     = $request->post('chatId');
        $attachment = $request->file('attachment');

        if (!in_array($attachment->getExtension(), ['jpg', 'jpeg'])) {
            $this->renderJson([
                'data' => [],
                'error' => true,
                'errorText' => 'Not valid extension',
            ]);
        }

        if ($attachment->getSizeInKb() > 5000) {
            $this->renderJson([
                'data' => [],
                'error' => true,
                'errorText' => 'Max file size in 5 MB',
            ]);
        }

        /** @var AttachmentService $attachmentService */
        $attachmentService = App::get('attachmentService');
        $attachment = $attachmentService->save($attachment, $chatId);

        $this->renderJson([
            'data' => [
                'attachmentId' => (int) $attachment['id'],
                'width' => $attachment['width'],
                'height' => $attachment['height'],
                'clientPath' => $attachment['clientPath'],
            ],
            'error' => false,
        ]);
    }
}
