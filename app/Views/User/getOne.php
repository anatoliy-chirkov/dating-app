<?php
/**
 * @var array $user
 * @var bool $isAuthorized
 * @var bool $isMe
 * @var array $images
 * @var array $userGoals
 */
?>

<link rel="stylesheet" href="/node_modules/photoswipe/dist/photoswipe.css">
<link rel="stylesheet" href="/node_modules/photoswipe/dist/default-skin/default-skin.css">

<div class="profile-page">
    <div class="main-info">
        <div class="avatar-box">
            <div
                    class="avatar"
                    style="background-image: url('<?=$user['clientPath']?>')"
            ></div>
        </div>
        <div class="title">
            <div style="display: flex; position: relative">
                <h1 style="margin-right: 10px"><?=$user['name']?>, <?=$user['age']?></h1>
                <?php if ($isMe): ?>
                <form method="post" action="/logout" style="margin: 0; display: flex; justify-content: center; flex-direction: column; position: absolute; right: 15px">
                    <button style="background: white; padding: 12px; margin: 0; border: none; border-radius: 50px" type="submit"><img height="16" src="/svg/logout.svg"></button>
                </form>
                <?php endif; ?>
            </div>
            <div class="city"><?=$user['city']?></div>
            <?=\Core\ServiceContainer::getInstance()->get('is_user_online_service')->getViewElement($user['sex'], $user['isConnected'], $user['lastConnected'])?>
            <div class="button-group">
                <?php if ($isAuthorized): ?>
                    <?php if (!$isMe): ?>
                        <a class="button" href="/user/<?=$user['id']?>/chat#last-message">Написать сообщение</a>
                    <?php else: ?>
                        <a class="button button-white" href="/profile">Настройки профиля</a>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="message-need-auth">Чтобы написать сообщение <a href="/login">войдите</a> или <a href="/register">зарегестрируйтесь</a></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="images">
        <?php $i = 0; foreach ($images as $image): ?>
            <div class="image" data-id="<?=$i?>" data-height="<?=$image['height']?>" data-width="<?=$image['width']?>" style="background-image: url('<?=$image['clientPath']?>')"></div>
        <?php $i++; endforeach; ?>
    </div>
    <div class="goals">
        <strong>Цели знакомства</strong>
        <br>
        <?php foreach ($userGoals as $userGoal): ?>
            <span><?=$userGoal['name']?></span>
        <?php endforeach; ?>
    </div>
    <div class="about">
        <?php if (!empty($user['about'])): ?>
            <strong>О себе</strong>
            <br>
            <span><?=$user['about']?></span>
        <?php endif; ?>
    </div>
    <div id="image-view" style="display: none">
        <img class="content" src="" alt="Image full view" height="80%">
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
    const items = [];

    $('.images').find('.image').map(function() {
        const backgroundImageVal = $(this).css('background-image');
        const backgroundImageUrl = backgroundImageVal.replace('url(','').replace(')','').replace(/\"/gi, "");
        items.push({src: backgroundImageUrl, h: $(this).attr('data-height'), w: $(this).attr('data-width')});
    });

    $('.profile-page').find('.images').find('.image').on('click', function () {
        const pswpElement = document.querySelector('.pswp');
        const options = {
            index: parseInt($(this).attr('data-id')),
            bgOpacity: 0.7,
            maxSpreadZoom: 1,
            getDoubleTapZoom: function (isMouseClick, item) {
                return item.initialZoomLevel;
            },
            // UI options
            zoomEl: false,
            //clickToCloseNonZoomable: false,
        };
        const gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
    });
</script>
