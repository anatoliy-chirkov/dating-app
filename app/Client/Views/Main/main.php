<?php
/**
 * @var array $men
 * @var array $women
 */
use Client\Services\LangService\Text;
?>

<div class="main">
    <div class="heading">
        <div class="title">
            <h1><?=Text::get('mainTitle')?></h1>
            <div><?=Text::get('mainSubtitle')?></div>
            <div class="register-button-wrap">
                <a href="/register" class="register-button"><?=Text::get('signUp')?></a>
            </div>
            <div style="font-size: 12px;margin-top: 20px;">
                <?=Text::get('mainDesc')?>
            </div>
        </div>
    </div>

    <?php if (!empty($women)): ?>
    <div class="results">
        <?php for ($i = 0; count($women) < 4 ? $i < count($women) : $i < 4; $i++): ?>
            <a href="/user/<?=$women[$i]['id']?>" class="profile">
                <?php if ($women[$i]['isTop']): ?>
                    <div class="profile-top">TOP</div>
                <?php endif; ?>
                <div class="image" style="background-image: url('<?=$women[$i]['clientPath']?>')"></div>
                <div class="about-wrap">
                    <div class="about"><span class="name"><?=$women[$i]['name']?></span>, <?=$women[$i]['age']?></div>
                    <div class="city"><?=$women[$i]['city']?></div>
                </div>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
<script>
    if (window.innerWidth > 855) {
        const first = $('.results').first();
        const second = $('.results').last();

        if (first.find('.profile').length === 2) {
            first.append('<a class="profile"></a>');
        }

        if (second.find('.profile').length === 2) {
            second.append('<a class="profile"></a>');
        }
    }
</script>
