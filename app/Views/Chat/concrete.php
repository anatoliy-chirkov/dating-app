<?php
/**
 * @var array $chats
 * @var array $messages
 * @var array $receiver
 * @var array $me
 */
?>
<div class="chats" id="messages-page" data-userId="<?=$receiver['id']?>">
    <div class="heading" style="display: flex; justify-content: space-between; border-bottom: 1px solid #efefef">
        <a href="/user/<?=$receiver['id']?>"><?=$receiver['name']?></a>
        <?=\Core\ServiceContainer::getInstance()->get('is_user_online_service')->getViewElement($receiver['sex'], $receiver['isConnected'], $receiver['lastConnected'])?>
    </div>

    <div class="chat-view">
        <div class="chat-list">
            <?php
            foreach ($chats as $chat):
                ?>
                <a href="/user/<?=$chat['userId']?>/chat#last-message" class="profile chat <?php if ($chat['userId'] === $receiver['id']): ?>active-chat<?php endif; ?>" data-id="<?=$chat['chatId']?>">
                    <div class="image" style="background-image: url('<?=$chat['clientPath']?>')">
                        <?=\Core\ServiceContainer::getInstance()->get('is_user_online_service')->getViewCircle($chat['isConnected'], $chat['lastConnected'])?>
                    </div>
                    <div class="about">
                        <div class="title"><?=$chat['name']?>, <?=$chat['age']?></div>
                        <div class="message">
                            <?php if (strlen($chat['text']) > 22): ?>
                                <?=mb_substr($chat['text'], 0, 22) . '…'?>
                            <?php else: ?>
                                <?=$chat['text']?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="label" <?php if ($chat['notReadCount'] > 0): ?> style="display: flex" <?php endif; ?>><?=$chat['notReadCount']?></div>
                </a>
            <?php
            endforeach;
            ?>
        </div>
        <div class="messages">
            <div class="messages-list">
                <?php
                if (count($messages) === 0):
                ?>
                    <div>Пока нет сообщений, напишите что-нибудь</div>
                <?php
                endif;
                ?>
                <?php $i = 0; foreach ($messages as $message): $i++ ?>
                    <div class="message">
                        <div class="about">
                            <div class="title"><?php if ($message['userId'] === $me['id']): ?>Вы<?php else: ?><?=$message['name']?><?php endif;?><span class="time"><?=$message['createdAt']?></span></div>
                            <div class="content"><?=$message['text']?></div>
                        </div>
                    </div>
                    <?php if (count($messages) === $i): ?>
                        <section id="last-message"></section>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <form id="chat-form" method="POST" data-receiverId="<?=$receiver['id']?>">
                <input type="text" name="text" placeholder="Ваше сообщение" autocomplete="off">
                <button type="submit">Отправить</button>
            </form>
        </div>
    </div>
</div>
