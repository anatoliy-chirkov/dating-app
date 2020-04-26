<?php

namespace Chat;

use Carbon\Carbon;
use Core\ServiceContainer;
use Ratchet\ConnectionInterface;
use Repositories\AttachmentRepository;
use Repositories\MessageRepository;
use Repositories\UserRepository\UserRepository;
use Services\ActionService\Action;
use Services\ActionService\IAction;

class Service
{
    private $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function authorize(object $payload, ConnectionInterface $conn)
    {
        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $user = $userRepository->getUserByToken($payload->token);

        if ($user === null) {
            $conn->close();
        } else {
            if (
                !Action::hasRestrictedProduct(IAction::HIDE_ONLINE)
                || !Action::check(IAction::HIDE_ONLINE, $user['id'])
            ) {
                $userRepository->setOnline($user['id']);
            }

            $this->store->setUserConnection($user['id'], $conn);
            $this->store->setUserData($user['id'], [
                'name' => $user['name'],
                'age'  => $user['age'],
                'path' => $user['clientPath'],
            ]);

            unset($user);
        }
    }

    public function message(object $payload, ConnectionInterface $conn)
    {
        $userId = $this->store->getUserIdByConnection($conn);

        if ($userId === false) {
            $conn->close();
        }

        $payloadForSend = $this->saveMessageAndGetPayloadForSend($userId, $payload->receiverId, $payload->text, $payload->attachmentId);

        if (empty($payloadForSend)) {
            return;
        }

        $reboundPayload = $payloadForSend;
        $reboundPayload['isRebound'] = true;
        $conn->send(@json_encode(['type' => IMessageType::MESSAGE, 'payload' => $reboundPayload]));

        $receiverConnection = $this->store->getConnectionByUserId($payload->receiverId);

        if ($receiverConnection !== null) {
            $receiverConnection->send(@json_encode(['type' => IMessageType::MESSAGE, 'payload' => $payloadForSend]));
        }
    }

    public function messageWasRead(object $payload, ConnectionInterface $conn)
    {
        /** @var MessageRepository $messageRepository */
        $messageRepository = ServiceContainer::getInstance()->get('message_repository');

        $messageRepository->messageWasRead($payload->messageId);
        $message = $messageRepository->getMessageById($payload->messageId);

        $authorConnection = $this->store->getConnectionByUserId($message['authorId']);

        if ($authorConnection !== null) {
            $authorConnection->send(json_encode([
                'type' => IMessageType::MESSAGE_WAS_READ,
                'payload' => [
                    'messageId' => $message['id'],
                    'chatId'    => $message['chatId'],
                ]
            ]));
        }
    }

    public function onCloseConnection(ConnectionInterface $conn)
    {
        $userId = $this->store->getUserIdByConnection($conn);

        if (
            !Action::hasRestrictedProduct(IAction::HIDE_ONLINE)
            || !Action::check(IAction::HIDE_ONLINE, $userId)
        ) {
            /** @var UserRepository $userRepository */
            $userRepository = ServiceContainer::getInstance()->get('user_repository');
            $userRepository->setOffline($userId);
        }

        $this->store->remove($conn);
    }

    private function saveMessageAndGetPayloadForSend(int $authorId, int $receiverId, ?string $text, ?int $attachmentId)
    {
        $chatRepository = ServiceContainer::getInstance()->get('chat_repository');
        $chatId = $chatRepository->getChatIdByUsers([$authorId, $receiverId]);

        if ($chatId === null) {

            if (!Action::run(IAction::SEND_MESSAGE, $authorId)) {
                return [];
            }

            $chatId = $chatRepository->createChat([$authorId, $receiverId]);
        }

        /** @var MessageRepository $messageRepository */
        $messageRepository = ServiceContainer::getInstance()->get('message_repository');
        $message = $messageRepository->addMessage($chatId, $authorId, $text);

        $attachment = [];
        if ($attachmentId !== null) {
            /** @var AttachmentRepository $attachmentRepository */
            $attachmentRepository = ServiceContainer::getInstance()->get('attachment_repository');
            $attachmentRepository->setMessageId($attachmentId, $message['id']);
            $attachment = $attachmentRepository->getById($attachmentId);
        }

        return [
            'id'        => $message['id'],
            'chatId'    => $chatId,
            'text'      => $text,
            'shortText' => strlen($text) > 22 ? mb_substr($text, 0, 22) . '…' : $text,
            'createdAt' => Carbon::parse($message['createdAt'])->locale('ru')
                ->isoFormat('D MMMM, HH:mm'),
            'user'   => [
                'id'    => $authorId,
                'name'  => $this->store->getUserData($authorId)['name'],
                'age'   => $this->store->getUserData($authorId)['age'],
                'image' => $this->store->getUserData($authorId)['path'],
            ],
            'attachment' => $attachment,
            'isRebound'  => false,
        ];
    }
}
