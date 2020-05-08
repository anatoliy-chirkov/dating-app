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
        /** @var ChatRepository $chatRepository */
        $chatRepository = App::get('chat');
        $chats = $chatRepository->getChatsByUserId($this->user['id']);

        return $this->render(['chats' => $chats]);
    }

    public function concrete(Request $request, $userId)
    {
        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $receiver = $userRepository->getById($userId);

        /** @var ChatRepository $chatRepository */
        $chatRepository = App::get('chat');
        $chatId = $chatRepository->getChatIdByUsers([$this->user['id'], $receiver['id']]);

        /** @var ChatRepository $chatRepository */
        $chatRepository = App::get('chat');
        $chats = $chatRepository->getChatsByUserId($this->user['id']);

        $isNewChat = false;

        if ($chatId !== null) {
            /** @var MessageRepository $messageRepository */
            $messageRepository = App::get('message');
            $messages = $messageRepository->getMessagesByChatId($chatId, self::MESSAGES_LIMIT_PER_REQUEST);

            foreach ($messages as &$message) {
                $message['createdAt'] = Carbon::parse($message['createdAt'])->locale('ru')
                    ->isoFormat('D MMMM, HH:mm');
            }

            $messageRepository->setAllMessagesWasRead($chatId, $this->user['id']);
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
                'errorText' => $validator->getErrorsAsString(),
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
        /** @var Validator $validator */
        $validator = App::get('validator', $request->all(), [
            'chatId' => 'required|integer',
            'attachment' => 'required|image|max:5000',
        ]);

        if (!$validator->isValid()) {
            $this->renderJson([
                'data' => [],
                'error' => true,
                'errorText' => $validator->getErrorsAsString(),
            ]);
        }

        /** @var AttachmentService $attachmentService */
        $attachmentService = App::get('attachmentService');
        $attachment = $attachmentService->save($request->file('attachment'), $request->post('chatId'));

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
