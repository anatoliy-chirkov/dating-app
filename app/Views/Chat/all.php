<?php
/**
 * @var array $chats
 */
?>
<div class="chats" id="chat-list-page">
    <div class="heading">Выберите диалог</div>

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
                <div>Выберите диалог или создайте новый. Начать новый диалог можно на странице интересующего пользователя</div>
            </div>
        </div>
    </div>
</div>
