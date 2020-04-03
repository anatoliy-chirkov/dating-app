<?php
/**
 * @var array $chats
 * @var array $messages
 * @var array $receiver
 * @var array $me
 */
?>
<div class="chats" id="messages-page">
    <div class="heading"><a href="/user/<?=$receiver['id']?>"><?=$receiver['name']?></a></div>

    <div class="chat-view">
        <div class="chat-list">
            <?php
            foreach ($chats as $chat):
                ?>
                <a href="/user/<?=$chat['userId']?>/chat" class="profile">
                    <div class="image" style="background-image: url('<?=str_replace('FrontendAssets', '', $chat['path'])?>')"></div>
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
                <?php
                foreach ($messages as $message):
                    ?>
                    <div class="message">
                        <div class="about">
                            <div class="title"><?php if ($message['userId'] === $me['id']): ?>Вы<?php else: ?><?=$message['name']?><?php endif;?><span class="time"><?=$message['createdAt']?></span></div>
                            <div class="content"><?=$message['text']?></div>
                        </div>
                    </div>
                <?
                endforeach;
                ?>
            </div>
            <form method="POST" action="/user/<?=$receiver['id']?>/chat">
                <input type="text" name="text" placeholder="Ваше сообщение" autocomplete="off">
                <button type="submit">Отправить</button>
            </form>
        </div>
    </div>
</div>
