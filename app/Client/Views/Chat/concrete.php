<?php

use Client\Services\ActionService\Action;
use Client\Services\ActionService\IAction;

/**
 * @var array $chats
 * @var array $messages
 * @var array $receiver
 * @var array $me
 * @var int $messagesCount
 * @var bool $isNewChat
 */
?>
<link rel="stylesheet" href="/node_modules/photoswipe/dist/photoswipe.css">
<link rel="stylesheet" href="/node_modules/photoswipe/dist/default-skin/default-skin.css">

<div class="chats" id="messages-page" data-userid="<?=$receiver['id']?>" data-messagescount="<?=$messagesCount?>">
    <div class="heading" style="display: flex; justify-content: space-between; border-bottom: 1px solid #efefef">
        <a href="/user/<?=$receiver['id']?>"><?=$receiver['name']?></a>
        <?=Shared\Core\App::get('onlineService')->getViewElement($receiver['sex'], $receiver['isConnected'], $receiver['lastConnected'])?>
    </div>

    <div class="chat-view">
        <div class="chat-list">
            <?php
            foreach ($chats as $chat):
                ?>
                <a href="/user/<?=$chat['userId']?>/chat#last-message" class="profile chat <?php if ($chat['userId'] === $receiver['id']): ?>active-chat<?php endif; ?>" data-id="<?=$chat['chatId']?>">
                    <div class="image" style="background-image: url('<?=$chat['clientPath']?>')">
                        <?=Shared\Core\App::get('onlineService')->getViewCircle($chat['isConnected'], $chat['lastConnected'])?>
                    </div>
                    <div class="about">
                        <div class="title"><?=$chat['name']?>, <?=$chat['age']?></div>
                        <div class="message">
                            <?php if (strlen($chat['text']) > 22): ?>
                                <?=mb_substr($chat['text'], 0, 22) . '…'?>
                            <?php else: ?>
                                <?=$chat['authorId'] === $me['id'] ? 'Вы: ' : ''?>
                                <?=!empty($chat['text']) ? $chat['text'] : 'Изображение'?>
                                <?=$chat['authorId'] === $me['id'] && !$chat['isRead'] ? '<span class="circle not-read"></span>' : ''?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="label" <?php if ($chat['notReadCount'] > 0 && $chat['userId'] !== $receiver['id']): ?> style="display: flex" <?php endif; ?>><?=$chat['notReadCount']?></div>
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
                    <div style="margin-top: 20px">Пока нет сообщений, напишите что-нибудь</div>
                <?php
                endif;
                ?>
                <?php $i = 0; foreach ($messages as $message): $i++ ?>
                    <?php $isYourMessage = $message['userId'] === $me['id']; ?>
                    <div class="message <?=$isYourMessage && !$message['isRead'] ? 'not-read' : ''?>" data-id="<?=$message['id']?>" data-isyour="<?=$isYourMessage ? 1 : 0?>">
                        <div class="about">
                            <div class="title" <?=!$isYourMessage ? 'style="color: #5183f5;"' : ''?>><?=$isYourMessage ? 'Вы' : $message['name']?><span class="time"><?=$message['createdAt']?></span></div>
                            <div class="content"><?=$message['text']?></div>
                            <?php if (!empty($message['attachment'])): ?>
                            <div class="attachment-wrap">
                                <div class="attachment-item"
                                     data-height="<?=$message['attachment']['height']?>"
                                     data-width="<?=$message['attachment']['width']?>"
                                     style="background-image: url('<?=$message['attachment']['clientPath']?>')"
                                ></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (count($messages) === $i): ?>
                        <section id="last-message"></section>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php if ($receiver['sex'] === 'woman' && !Action::check(IAction::SEND_MESSAGE_TO_GIRL, $me['id'])): ?>
                <div style="margin-left: 20px; margin-top: 14px">
                    Для общения с девушками приобретите <a href="/shop"><?=Action::productGroupName(IAction::SEND_MESSAGE_TO_GIRL)?></a>
                </div>
            <?php elseif ($isNewChat && !Action::checkCounter(IAction::SEND_MESSAGE, $me['id'])): ?>
                <div style="margin-left: 20px; margin-top: 14px">
                    Для общения с новыми людьми пополните счетчик <a href="/shop"><?=Action::counterName(IAction::SEND_MESSAGE)?></a>
                </div>
            <?php else: ?>
            <form id="chat-form" method="POST" data-receiverId="<?=$receiver['id']?>">
                <div class="form-main">
                    <div class="attachment">
                        <svg fill="#aaa" height="22" viewBox="-96 0 512 512" width="22" xmlns="http://www.w3.org/2000/svg"><path d="m160 512c-88.234375 0-160-71.765625-160-160v-224c0-11.796875 9.558594-21.332031 21.332031-21.332031 11.777344 0 21.335938 9.535156 21.335938 21.332031v224c0 64.683594 52.628906 117.332031 117.332031 117.332031s117.332031-52.648437 117.332031-117.332031v-234.667969c0-41.171875-33.492187-74.664062-74.664062-74.664062-41.175781 0-74.667969 33.492187-74.667969 74.664062v213.335938c0 17.640625 14.355469 32 32 32s32-14.359375 32-32v-202.667969c0-11.796875 9.558594-21.332031 21.332031-21.332031 11.777344 0 21.335938 9.535156 21.335938 21.332031v202.667969c0 41.171875-33.496094 74.664062-74.667969 74.664062s-74.667969-33.492187-74.667969-74.664062v-213.335938c0-64.679687 52.628907-117.332031 117.335938-117.332031 64.703125 0 117.332031 52.652344 117.332031 117.332031v234.667969c0 88.234375-71.765625 160-160 160zm0 0"/></svg>                </div>
                    <input id="file-input" type="file" name="file" hidden>
                    <input type="text" name="text" placeholder="Ваше сообщение" autocomplete="off">
                    <button type="submit">Отправить</button>
                </div>
                <div class="attachment-content"></div>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <!-- PhotoSwipe Begin -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <!-- Background of PhotoSwipe.
             It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>
        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">
            <!-- Container that holds slides.
                PhotoSwipe keeps only 3 of them in the DOM to save memory.
                Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <!--  Controls are self-explanatory. Order can be changed. -->
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <!--                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>-->

                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                </button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                </button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- PhotoSwipe End -->
</div>
<script src="/node_modules/photoswipe/dist/photoswipe.min.js"></script>
<script src="/node_modules/photoswipe/dist/photoswipe-ui-default.min.js"></script>
<script>
    // add attachment
    $('#chat-form .attachment').on('click', function() {
        $('#file-input').click();
    });

    $('#file-input').on('change', function(e) {
        const fileName = e.target.files[0].name;
        const $attachmentContent = $('.attachment-content');
        $attachmentContent.html(`<span>${fileName}</span> <span id="clear-attachment"><?php require  'FrontendAssets/svg/close.svg'; ?></span>`);
        $attachmentContent.css('display', 'block');

        $('.chats .chat-view .messages .messages-list').css('height', 'calc(100vh - 296px)');

        $('#clear-attachment').on('click', function() {
            document.getElementById('file-input').value = '';

            const $attachmentContent = $('.attachment-content');
            $attachmentContent.html('');
            $attachmentContent.css('display', 'none');

            $('.chats .chat-view .messages .messages-list').css('height', 'calc(100vh - 266px)');
        });
    });
</script>
