<?php
/**
 * @var array $chats
 */
?>
<div class="chats">
    <div class="heading"></div>
    <div class="chat-list">
        <?php
        foreach ($chats as $chat):
            ?>
            <a href="/user/<?=$chat['userId']?>/chat" class="profile">
                <div class="image" style="background-image: url('<?=str_replace('FrontendAssets', '', $chat['path'])?>')"></div>
                <div class="about">
                    <div class="title"><?=$chat['name']?>, <?=$chat['age']?></div>
                    <div class="message"><?=$chat['text']?></div>
                </div>
            </a>
        <?php
        endforeach;
        ?>
    </div>
</div>
