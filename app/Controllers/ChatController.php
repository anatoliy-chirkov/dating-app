<?php

namespace Controllers;

use Core\Controllers\BaseController;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;
use Core\Validation\Validator;
use Repositories\ChatRepository;
use Repositories\MessageRepository;
use Repositories\UserRepository\UserRepository;
use Services\AttachmentService;
use Services\AuthService;

class ChatController extends BaseController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['all', 'concrete'];
    }

    public function all(Request $request)
    {
        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');
        $user = $authService->getUser();

        /** @var ChatRepository $chatRepository */
        $chatRepository = ServiceContainer::getInstance()->get('chat_repository');
        $chats = $chatRepository->getChatsByUserId($user['id']);

        return $this->render(['chats' => $chats]);
    }

    public function concrete(Request $request, $userId)
    {
        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');
        $me = $authService->getUser();

        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $receiver = $userRepository->getById($userId);

        /** @var ChatRepository $chatRepository */
        $chatRepository = ServiceContainer::getInstance()->get('chat_repository');
        $chatId = $chatRepository->getChatIdByUsers([$me['id'], $receiver['id']]);

        /** @var ChatRepository $chatRepository */
        $chatRepository = ServiceContainer::getInstance()->get('chat_repository');
        $chats = $chatRepository->getChatsByUserId($me['id']);

        if ($chatId !== null) {
            /** @var MessageRepository $messageRepository */
            $messageRepository = ServiceContainer::getInstance()->get('message_repository');
            $messages = $messageRepository->getMessagesByChatId($chatId);

            // $messageRepository->setAllMessagesWasRead($chatId, $me['id']);
        } else {
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

        return $this->render(['chats' => $chats, 'receiver' => $receiver, 'messages' => $messages]);
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
        $attachmentService = ServiceContainer::getInstance()->get('attachment_service');
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
