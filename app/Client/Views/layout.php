<?php
/**
 * @var $socketUrl string
 * @var $title string
 * @var $description string
 * @var $innerViewPath string
 * @var $notification Client\Services\NotificationService\Notification
 * @var $isAuthorized bool
 * @var array $me
 * @var int $countNotReadMessages
 * @var int $countNotSeenVisits
 */
use Client\Services\LangService\Text;
?>
<html lang="en">
<head>
    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1">

    <!--    <link rel="stylesheet" href="/css/main.css">-->
    <link rel="stylesheet" href="/css/general/base.css">
    <link rel="stylesheet" href="/css/general/nav.css">
    <link rel="stylesheet" href="/css/general/main-content.css">
    <link rel="stylesheet" href="/css/general/notification.css">

    <link rel="stylesheet" href="/css/my.css">
    <link rel="stylesheet" href="/node_modules/select2/dist/css/select2.css">

</head>
<body>
<script type="application/javascript" src="/node_modules/jquery/dist/jquery.min.js"></script>
<?php if (!empty($me)): ?>
    <span id="user-token" data-token="<?=$_COOKIE['_token']?>"></span>
<?php endif; ?>
<nav class="nav">
    <div class="nav-container">
        <div class="brand">
            <a aria-current="page" class="" href="/">

                <div style="background-image: url('/img/ankira-shortcut-color.png');
                height: 37px;
    width: 37px;
    background-size: contain;
    background-repeat: no-repeat;"></div>
<!--                <svg class="logo" xmlns="http://www.w3.org/2000/svg" width="100px" height="40px">-->
<!--                    <text kerning="auto" font-family="Myriad Pro" fill="rgb(0, 0, 0)" font-size="24px" x="0px" y="28px"><tspan font-size="24px" font-family="Chalkboard" fill="#000000">Ankira</tspan></text>-->
<!--                </svg>-->
<!--                <svg class="mobile-logo" xmlns="http://www.w3.org/2000/svg" width="52px" height="40px">-->
<!--                    <text kerning="auto" font-family="Myriad Pro" fill="rgb(0, 0, 0)" font-size="24px" x="0px" y="28px"><tspan font-size="24px" font-family="Chalkboard" fill="#000000">Ankr</tspan></text>-->
<!--                </svg>-->
            </a>
        </div>
        <div class="links">
            <a href="/search">
                <?php if (strpos($_SERVER['REQUEST_URI'], '/search') !== false || $_SERVER['REQUEST_URI'] === '/'): ?>
                    <svg aria-label="Поиск" fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M47.6 44L35.8 32.2C38.4 28.9 40 24.6 40 20 40 9 31 0 20 0S0 9 0 20s9 20 20 20c4.6 0 8.9-1.6 12.2-4.2L44 47.6c.6.6 1.5.6 2.1 0l1.4-1.4c.6-.6.6-1.6.1-2.2zM20 35c-8.3 0-15-6.7-15-15S11.7 5 20 5s15 6.7 15 15-6.7 15-15 15z"></path></svg>
                <?php else: ?>
                    <svg aria-label="Поиск" fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M20 40C9 40 0 31 0 20S9 0 20 0s20 9 20 20-9 20-20 20zm0-37C10.6 3 3 10.6 3 20s7.6 17 17 17 17-7.6 17-17S29.4 3 20 3z"></path><path d="M46.6 48.1c-.4 0-.8-.1-1.1-.4L32 34.2c-.6-.6-.6-1.5 0-2.1s1.5-.6 2.1 0l13.5 13.5c.6.6.6 1.5 0 2.1-.2.3-.6.4-1 .4z"></path></svg>
                <?php endif; ?>
            </a>
            <? if ($isAuthorized): ?>
            <a href="/visits">
                <div id="visit-tip" <?php if ($countNotSeenVisits > 0): ?> style="display: flex" <?php endif; ?>><?=$countNotSeenVisits?></div>
                <?php if (strpos($_SERVER['REQUEST_URI'], '/visits') !== false): ?>
                    <svg aria-label="Визиты" fill="#262626" enable-background="new 0 0 515.556 515.556" height="24" viewBox="0 0 515.556 515.556" width="24"><path d="m257.778 64.444c-119.112 0-220.169 80.774-257.778 193.334 37.609 112.56 138.666 193.333 257.778 193.333s220.169-80.774 257.778-193.333c-37.609-112.56-138.666-193.334-257.778-193.334zm0 322.223c-71.184 0-128.889-57.706-128.889-128.889 0-71.184 57.705-128.889 128.889-128.889s128.889 57.705 128.889 128.889c0 71.182-57.705 128.889-128.889 128.889z"/><path d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138 65.971-25.167 91.138 0"/></svg>
                <?php else: ?>
                    <svg aria-label="Визиты" fill="#262626" enable-background="new 0 0 551.121 551.121" height="24" viewBox="0 0 551.121 551.121" width="24"><path d="m275.561 68.887c-123.167 0-233.534 80.816-274.64 201.107-1.228 3.616-1.228 7.518 0 11.134 41.106 120.291 151.473 201.107 274.64 201.107s233.534-80.816 274.64-201.107c1.228-3.616 1.228-7.518 0-11.134-41.107-120.291-151.474-201.107-274.64-201.107zm0 378.902c-106.532 0-202.284-68.975-240.077-172.228 37.793-103.253 133.544-172.228 240.077-172.228 106.532 0 202.284 68.975 240.077 172.228-37.793 103.253-133.545 172.228-240.077 172.228z"/><path d="m275.561 172.224c-56.983 0-103.337 46.354-103.337 103.337s46.354 103.337 103.337 103.337 103.337-46.353 103.337-103.337-46.354-103.337-103.337-103.337zm0 172.228c-37.995 0-68.891-30.897-68.891-68.891 0-37.995 30.897-68.891 68.891-68.891 37.995 0 68.891 30.897 68.891 68.891s-30.897 68.891-68.891 68.891z"/></svg>
                <?php endif; ?>
            </a>
            <a href="/chats">
                <div id="message-tip" <?php if ($countNotReadMessages > 0): ?> style="display: flex" <?php endif; ?>><?=$countNotReadMessages?></div>
                <?php if (strpos($_SERVER['REQUEST_URI'], 'chat') !== false): ?>
                    <?php require CLIENT_PATH . '/FrontendAssets/svg/mail_.svg'; ?>
                <?php else: ?>
                    <?php require CLIENT_PATH . '/FrontendAssets/svg/mail.svg'; ?>
                <?php endif; ?>
            </a>
            <a href="/shop">
                <?php if (strpos($_SERVER['REQUEST_URI'], '/shop') !== false): ?>
                    <svg aria-label="Услуги" fill="#262626" height="24" viewBox="0 -31 512.00033 512"><path d="m166 300.003906h271.003906c6.710938 0 12.597656-4.4375 14.414063-10.882812l60.003906-210.003906c1.289063-4.527344.40625-9.390626-2.433594-13.152344-2.84375-3.75-7.265625-5.964844-11.984375-5.964844h-365.632812l-10.722656-48.25c-1.523438-6.871094-7.617188-11.75-14.648438-11.75h-91c-8.289062 0-15 6.710938-15 15 0 8.292969 6.710938 15 15 15h78.960938l54.167968 243.75c-15.9375 6.929688-27.128906 22.792969-27.128906 41.253906 0 24.8125 20.1875 45 45 45h271.003906c8.292969 0 15-6.707031 15-15 0-8.289062-6.707031-15-15-15h-271.003906c-8.261719 0-15-6.722656-15-15s6.738281-15 15-15zm0 0"/><path d="m151 405.003906c0 24.816406 20.1875 45 45.003906 45 24.8125 0 45-20.183594 45-45 0-24.8125-20.1875-45-45-45-24.816406 0-45.003906 20.1875-45.003906 45zm0 0"/><path d="m362.003906 405.003906c0 24.816406 20.1875 45 45 45 24.816406 0 45-20.183594 45-45 0-24.8125-20.183594-45-45-45-24.8125 0-45 20.1875-45 45zm0 0"/></svg>
                <?php else: ?>
                    <svg aria-label="Услуги" fill="#262626" height="24" viewBox="0 -31 512.00026 512" width="24"><path d="m164.960938 300.003906h.023437c.019531 0 .039063-.003906.058594-.003906h271.957031c6.695312 0 12.582031-4.441406 14.421875-10.878906l60-210c1.292969-4.527344.386719-9.394532-2.445313-13.152344-2.835937-3.757812-7.269531-5.96875-11.976562-5.96875h-366.632812l-10.722657-48.253906c-1.527343-6.863282-7.613281-11.746094-14.644531-11.746094h-90c-8.285156 0-15 6.714844-15 15s6.714844 15 15 15h77.96875c1.898438 8.550781 51.3125 230.917969 54.15625 243.710938-15.941406 6.929687-27.125 22.824218-27.125 41.289062 0 24.8125 20.1875 45 45 45h272c8.285156 0 15-6.714844 15-15s-6.714844-15-15-15h-272c-8.269531 0-15-6.730469-15-15 0-8.257812 6.707031-14.976562 14.960938-14.996094zm312.152343-210.003906-51.429687 180h-248.652344l-40-180zm0 0"/><path d="m150 405c0 24.8125 20.1875 45 45 45s45-20.1875 45-45-20.1875-45-45-45-45 20.1875-45 45zm45-15c8.269531 0 15 6.730469 15 15s-6.730469 15-15 15-15-6.730469-15-15 6.730469-15 15-15zm0 0"/><path d="m362 405c0 24.8125 20.1875 45 45 45s45-20.1875 45-45-20.1875-45-45-45-45 20.1875-45 45zm45-15c8.269531 0 15 6.730469 15 15s-6.730469 15-15 15-15-6.730469-15-15 6.730469-15 15-15zm0 0"/></svg>
                <?php endif; ?>
            </a>
            <a href="/user/<?=$me['id']?>">
                <div style="background-image: url('<?=$me['clientPath']?>'); height: 24px; width: 24px; background-size: cover; background-position: center center; border-radius: 24px;"></div>
            </a>
            <? endif; ?>
        </div>
    </div>
</nav>
<main id="main-content">
    <?php if($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/login' || $_SERVER['REQUEST_URI'] === '/register'): ?>
        <div class="main-background <?php if ($_SERVER['REQUEST_URI'] === '/login' || $_SERVER['REQUEST_URI'] === '/register'): ?>entry<?php endif; ?>">
            <div class="inner"></div>
        </div>
    <?php endif; ?>

    <div class="container">
        <? if (!isset($LAYOUT_NOTIFICATION_OFF) && $notification->isset()): ?>
            <div class="notification <?=$notification->type?>"><?= $notification->message ?></div>
        <? endif; ?>

        <? require_once $innerViewPath; ?>

        <div id="message-notification">
            <div class="title">Новое сообщение</div>
            <div class="close">
                <svg aria-label="Закрыть" fill="#989898" height="10" width="10" viewBox="0 0 492 492">
                    <path d="M300.188,246L484.14,62.04c5.06-5.064,7.852-11.82,7.86-19.024c0-7.208-2.792-13.972-7.86-19.028L468.02,7.872
			c-5.068-5.076-11.824-7.856-19.036-7.856c-7.2,0-13.956,2.78-19.024,7.856L246.008,191.82L62.048,7.872
			c-5.06-5.076-11.82-7.856-19.028-7.856c-7.2,0-13.96,2.78-19.02,7.856L7.872,23.988c-10.496,10.496-10.496,27.568,0,38.052
			L191.828,246L7.872,429.952c-5.064,5.072-7.852,11.828-7.852,19.032c0,7.204,2.788,13.96,7.852,19.028l16.124,16.116
			c5.06,5.072,11.824,7.856,19.02,7.856c7.208,0,13.968-2.784,19.028-7.856l183.96-183.952l183.952,183.952
			c5.068,5.072,11.824,7.856,19.024,7.856h0.008c7.204,0,13.96-2.784,19.028-7.856l16.12-16.116
			c5.06-5.064,7.852-11.824,7.852-19.028c0-7.204-2.792-13.96-7.852-19.028L300.188,246z"/>
                </svg>
            </div>
            <a class="body">
                <div class="user-image"></div>
                <div class="more">
                    <span class="user-name"></span>
                    <span class="user-message"></span>
                </div>
            </a>
        </div>
    </div>
</main>
<footer>
    <div class="container">
        <div class="bottom-block">
            <span>© Ankira Dating App 2020</span>
        </div>
    </div>
</footer>
<script>
    const attachments = [];

    $(document).ready(function() {
        if ($('#user-token').length > 0) {
            const socket = new WebSocket('<?=$socketUrl?>');
            const userToken = $('#user-token').attr('data-token');
            socket.onopen = (e) => {
                socket.send(JSON.stringify({type: "AUTHORIZE", payload: {token: userToken}}));
                if ($messagePage.length > 0) {
                    $('.message[data-isyour="0"]').map(function () {
                        const id = parseInt($(this).attr('data-id'));
                        socket.send(JSON.stringify({type: "MESSAGE_WAS_READ", payload: {messageId: id}}));
                    });
                }
            };

            $('#message-notification').find('.close').on('click', function() {
                $(this).parent().fadeOut();
            });

            const chatWindow = new ChatWindow();
            chatWindow.submitChatFormHandler(socket);

            const $messagePage = $('#messages-page');

            socket.onmessage = function(e) {
                const data = JSON.parse(e.data);
                const payload = data.payload;
                const notification = new Notification();

                switch (data.type) {
                    case 'MESSAGE':
                        if (payload.isRebound) {
                            chatWindow.addMyChatMessageToDOM(payload.id, payload.text, payload.shortText, payload.createdAt, payload.attachment);
                        } else {
                            notification.show(payload.id, payload.chatId, payload.text, payload.shortText, payload.user, payload.createdAt, payload.attachment);
                            if ($messagePage.length > 0 && parseInt($messagePage.attr('data-userid')) === parseInt(payload.user.id)) {
                                socket.send(JSON.stringify({type: "MESSAGE_WAS_READ", payload: {messageId: parseInt(payload.id)}}));
                            }
                        }
                        break;
                    case 'MESSAGE_WAS_READ':
                        if ($messagePage.length > 0) {
                            const $notReadCircle = $(`.profile.chat[data-id="${parseInt(payload.chatId)}"]`)
                                .find('.circle.not-read');

                            if ($notReadCircle.length > 0) {
                                $notReadCircle.remove();
                            }

                            $(`.message.not-read[data-id="${payload.messageId}"]`).removeClass('not-read');
                        }
                        break;
                    default:
                        break;
                }
            };

            if ($messagePage.length > 0) {
                $('.attachment-item').map(function() {
                    const backgroundImageVal = $(this).css('background-image');
                    const backgroundImageUrl = backgroundImageVal.replace('url(','').replace(')','').replace(/\"/gi, "");
                    const obj = {src: backgroundImageUrl, h: $(this).attr('data-height'), w: $(this).attr('data-width')};
                    attachments.push(obj);
                    $(this).attr('data-id', attachments.indexOf(obj));
                });

                $('.messages-list').on('click', '.attachment-item', function () {
                    onAttachmentClick($(this).attr('data-id'))
                });

                chatWindow.bindOnScroll();
            }
        }
    });

    function onAttachmentClick(dataId) {
        const pswpElement = document.querySelector('.pswp');
        const options = {
            index: parseInt(dataId),
            bgOpacity: 0.7,
            maxSpreadZoom: 1,
            getDoubleTapZoom: function (isMouseClick, item) {
                return item.initialZoomLevel;
            },
            // UI options
            zoomEl: false,
            //clickToCloseNonZoomable: false,
        };
        const gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, attachments, options);
        gallery.init();
    }

    class ChatWindow {
        submitChatFormHandler(socket) {
            $('#chat-form').on('submit', (e) => {
                e.preventDefault();

                const self = this;
                const receiverId = parseInt($(e.target).attr('data-receiverId'));
                const $textInput = $(e.target).find('input[name="text"]');
                const text = $textInput.val();

                const fileInput = document.getElementById('file-input');
                const file = fileInput.files[0];
                if (file !== undefined) {
                    const formData = new FormData();
                    formData.append('attachment', file);
                    const chatId = $('.profile.active-chat').attr('data-id');
                    formData.append('chatId', parseInt(chatId));
                    $.ajax({
                        url: '/save-message-attachment',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.data.error !== true && response.data.attachmentId !== undefined) {
                                self.sendMessageToServer(socket, $textInput, receiverId, text, response.data.attachmentId);
                                fileInput.value = '';
                                const $attachmentContent = $('.attachment-content');
                                $attachmentContent.html('');
                                $attachmentContent.css('display', 'none');
                                $('.chats .chat-view .messages .messages-list').css('height', 'calc(100vh - 266px)');
                                $textInput.val('');
                            } else {
                                alert(response.errorText);
                            }
                        },
                    });
                } else {
                    if (text !== '') {
                        self.sendMessageToServer(socket, $textInput, receiverId, text);
                        $textInput.val('');
                    }
                }
            });
        };

        sendMessageToServer(socket, textInput, receiverId, text, attachmentId = null) {
            if ((text !== '' || attachmentId !== null) && receiverId > 0) {
                socket.send(JSON.stringify({type: "MESSAGE", payload: {text: text, receiverId: receiverId, attachmentId: attachmentId}}));
            }
        }

        addMessageToChatList(chatId, shortText, user) {
            const chat = $(`.chat[data-id="${chatId}"]`);
            const isActive = parseInt($('.active-chat').attr('data-id')) === parseInt(chatId);

            if (chat.length > 0) {
                const chatDOM = chat.clone();
                chat.remove();
                $('.chat-list').prepend(chatDOM);
                const username = user.name === 'Вы' ? 'Вы: ' : '';
                const text = shortText === '' ? 'Изображение' : shortText;
                const notReadCircle = user.name === 'Вы' ? '<span class="circle not-read"></span>' : '';

                chatDOM.find('.message').text(username + text);
                chatDOM.find('.message').append(notReadCircle);

                if (!isActive) {
                    const label = chatDOM.find('.label');
                    label.text(parseInt(label.text()) + 1);
                    label.css('display', 'flex');
                }
            } else {
                const chatDOM = `<a href="/user/${user.id}/chat#last-message" class="profile chat ${isActive && 'active-chat'}" data-id="${chatId}">
                <div class="image" style="background-image: url('${user.image}')"></div>
                <div class="about">
                    <div class="title">${user.name}, ${user.age}</div>
                    <div class="message">${user.name === 'Вы' ? 'Вы: ' : ''}${shortText === '' ? 'Изображение' : shortText}</div>
                    ${user.name === 'Вы' ? '<span class="circle not-read"></span>' : ''}
                </div>
                ${!isActive && '<div class="label" style="display: flex">1</div>'}
            </a>`;
                $('.chat-list').prepend(chatDOM);
            }
        };

        addMyChatMessageToDOM(id, text, shortText, createdAt, attachment = {}) {
            const chatId = $('.active-chat').attr('data-id');

            this.addMessageToChat(id, createdAt, text, {name: 'Вы'}, attachment);
            this.addMessageToChatList(chatId, shortText, {name: 'Вы', id: null, age: null});
        };

        addMessageToChat(id, createdAt, text, user, attachment) {
            let attachmentIndex = null;

            if (Object.keys(attachment).length !== 0) {
                const obj = {src: attachment.clientPath, h: attachment.height, w: attachment.width};
                attachments.push(obj);
                attachmentIndex = attachments.indexOf(obj);
            }

            const messageDOM =
`<div class="message ${user.name === 'Вы' ? 'not-read' : ''}" data-id="${id}">
    <div class="about">
        <div class="title" ${user.name === 'Вы' ? '' : 'style="color: #5183f5;"'}>${user.name}<span class="time">${createdAt}</span></div>
        <div class="content">${text}</div>
        ${Object.keys(attachment).length !== 0 ?
            `<div class="attachment-wrap">
            <div class="attachment-item" data-id="${attachmentIndex}"
            data-height="${attachment.height}"
            data-width="${attachment.width}"
            style="background-image: url('${attachment.clientPath}')"
            ></div>
            </div>` : ''
        }
    </div>
</div>`;

            $('.messages-list').append(messageDOM);

            const messagesList = document.getElementsByClassName('messages-list')[0];
            messagesList.scrollTop = messagesList.scrollHeight;
        };

        bindOnScroll() {
            let loading = false;

            $('.messages-list').scroll(function() {
                const $messagesList = $(this);
                const $messagesPage = $('#messages-page');

                const chatId = parseInt($('.profile.chat.active-chat').attr('data-id'));
                const messagesCount = $messagesPage.attr('data-messagescount');
                const offset = $('.message').length;
                const receiverId = parseInt($messagesPage.attr('data-userid'));

                if ($(this).scrollTop() < 350 && messagesCount > offset && loading === false) {
                    loading = true;
                    $messagesList.css('overflow-y', 'hidden');
                    $.get(`/chat/${chatId}/get-messages?offset=${offset}`, function (response) {
                        const data = response.data;
                        if (Array.isArray(data.messages) && data.messages.length > 0) {
                            data.messages.map(function(item) {
                                if (item.attachment !== null) {
                                    const obj = {src: item.attachment.clientPath, h: item.attachment.height, w: item.attachment.width};
                                    attachments.unshift(obj);
                                    $('.attachment-item').map(function() {
                                        const currentDataId = parseInt($(this).attr('data-id'));
                                        $(this).attr('data-id', currentDataId + 1);
                                    });
                                }
                                const isYourMessage = receiverId !== item.authorId;
                                $messagesList.prepend(`
                    <div class="message ${isYourMessage && !item.isRead ? 'not-read' : ''}" data-id="${item.id}" data-isyour="${isYourMessage}">
                    <div class="about">
                        <div class="title" ${isYourMessage ? 'style="color: #5183f5;"' : ''}>${isYourMessage ? 'Вы' : item.name}<span class="time">${item.createdAt}</span></div>
                        <div class="content">${item.text}</div>
                        ${item.attachment !== null ?
                                    `<div class="attachment-wrap">
                            <div class="attachment-item" data-id="0"
                            data-height="${item.attachment.height}"
                            data-width="${item.attachment.width}"
                            style="background-image: url('${item.attachment.clientPath}')"
                            ></div>
                        </div>`
                                    : ''}
                    </div>
                    </div>
                        `);
                            });
                            loading = false;
                        }
                        $messagesList.css('overflow-y', 'scroll');
                    });
                }
            });
        }
    }

    class NavBar {
        static addNewMessage() {
            const messageTip = $('#message-tip'); console.log(messageTip);
            if (messageTip.text() === '0') {
                messageTip.css('display', 'flex');
                messageTip.text(1);
            } else {
                messageTip.text(parseInt(messageTip.text()) + 1);
            }
        };
    }

    class Notification {
        showNewMessagePopUp (text, user) {
            const mNotification = $('#message-notification');
            mNotification.css('display', 'block');
            mNotification.find('.user-image').css('background-image', `url(${user.image})`);
            mNotification.find('.user-name').text(user.name);
            mNotification.find('.user-message').text(text === '' ? 'Изображение' : text);
            mNotification.find('.body').attr('href', `/user/${user.id}/chat#last-message`);

            setTimeout(function() {
                mNotification.fadeOut();
            }, 3000)
        };

        addNewMessageTagToNav() {
            NavBar.addNewMessage();
        };

        show(id, chatId, text, shortText, user, createdAt, attachment) {
            const messagesArea = $('#messages-page');

            if (messagesArea.length > 0 || $('#chat-list-page').length > 0) {
                const chatWindow = new ChatWindow();
                chatWindow.addMessageToChatList(chatId, shortText, user);

                if (messagesArea.length > 0 && parseInt(messagesArea.attr('data-userid')) === user.id) {
                    chatWindow.addMessageToChat(id, createdAt, text, user, attachment);
                } else {
                    this.addNewMessageTagToNav();
                }
            } else {
                this.showNewMessagePopUp(text, user);
                this.addNewMessageTagToNav();
            }
        };
    }

    $(window).scroll(function() {
        const scroll = $(window).scrollTop();

        if (scroll === 0) {
            $(".nav").removeClass("scroll");
        } else if (!$(".nav").hasClass("scroll")) {
            $(".nav").addClass("scroll");
        }
    });
</script>
</body>
</html>
