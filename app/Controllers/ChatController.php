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

            if ($request->isPost()) {
                /** @var Validator $validator */
                $validator = ServiceContainer::getInstance()->get('validator');

                if ($validator->isValid($request->post(), ['text' => 'required'])) {
                    if ($chatId === null) {
                        $chatId = $chatRepository->createChat([(int) $me['id'], (int) $receiver['id']]);
                    }

                    /** @var MessageRepository $messageRepository */
                    $messageRepository = ServiceContainer::getInstance()->get('message_repository');
                    $messageRepository->addMessage($chatId, $me['id'], $request->post('text'));
                }
            }

        if ($chatId !== null) {
            /** @var MessageRepository $messageRepository */
            $messageRepository = ServiceContainer::getInstance()->get('message_repository');
            $messages = $messageRepository->getMessagesByChatId($chatId);
        } else {
            $messages = [];
        }

        /** @var ChatRepository $chatRepository */
        $chatRepository = ServiceContainer::getInstance()->get('chat_repository');
        $chats = $chatRepository->getChatsByUserId($me['id']);

        return $this->render(['chats' => $chats, 'receiver' => $receiver, 'messages' => $messages, 'me' => $me]);
    }
}
