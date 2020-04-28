<?php
/**
 * @var array $chats
 */
use Client\Services\LangService\Text;
?>
<div class="chats" id="chat-list-page">
    <div class="heading" style="border-bottom: 1px solid #efefef"><?=Text::get('chooseChat')?></div>

    <div class="chat-view">
        <div class="chat-list">
            <?php
            foreach ($chats as $chat):
                ?>
                <a href="/user/<?=$chat['userId']?>/chat#last-message" class="profile chat" data-id="<?=$chat['chatId']?>">
                    <div class="image" style="background-image: url('<?=$chat['clientPath']?>')">
                        <?=Shared\Core\App::get('onlineService')->getViewCircle($chat['isConnected'], $chat['lastConnected'])?>
                    </div>
                    <div class="about">
                        <div class="title"><?=$chat['name']?>, <?=$chat['age']?></div>
                        <div class="message">
                            <?php if (strlen($chat['text']) > 22): ?>
                                <?=mb_substr($chat['text'], 0, 22) . '…'?>
                            <?php else: ?>
                                <?=$chat['authorId'] === $me['id'] ? Text::get('you') . ': ' : ''?>
                                <?=!empty($chat['text']) ? $chat['text'] : Text::get('image')?>
                                <?=$chat['authorId'] === $me['id'] && !$chat['isRead'] ? '<span class="circle not-read"></span>' : ''?>
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
                <div style="
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                    justify-content: center;
                    text-align: center;
                ">
                    <div><?=Text::get('chatStub')?></div>
                </div>
            </div>
        </div>
    </div>
</div>
