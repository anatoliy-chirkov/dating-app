const content = $('.window');
const settingList = $('.setting-list');
const currentLink = $('#current-setting');

function hideMenuAndShowContent() {
    settingList.addClass('mobile-hide');
    content.removeClass('mobile-hide');
}

function showMenuAndHideContent() {
    settingList.removeClass('mobile-hide');
    content.addClass('mobile-hide');
}

currentLink.on('click', function() {
    hideMenuAndShowContent();
});

$('.mobile-back').on('click', function() {
    if (content.hasClass('mobile-hide')) {
        hideMenuAndShowContent();
    } else {
        showMenuAndHideContent();
    }
});
