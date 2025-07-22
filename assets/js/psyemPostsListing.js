
var lFollowX = 0,
    lFollowY = 0,
    x = 0,
    y = 0,
    friction = 1 / 30;

function moveBackground() {
    x += (lFollowX - x) * friction;
    y += (lFollowY - y) * friction;
    translate = 'translate(' + x + 'px, ' + y + 'px) scale(1.1)';
    jQuery('.bgImageknow').css({
        '-webit-transform': translate,
        '-moz-transform': translate,
        'transform': translate
    });
    window.requestAnimationFrame(moveBackground);
}

jQuery(window).on('mousemove click', function (e) {
    var lMouseX = Math.max(-100, Math.min(100, jQuery(window).width() / 2 - e.clientX));
    var lMouseY = Math.max(-100, Math.min(100, jQuery(window).height() / 2 - e.clientY));
    lFollowX = (20 * lMouseX) / 100; // 100 : 12 = lMouxeX : lFollow
    lFollowY = (10 * lMouseY) / 100;
});

moveBackground();

jQuery(function () {

    jQuery(document).on('input', '.searchSection input.search-text', function (ev) {
        if (jQuery(this).val() === '') {
            jQuery('.search-submit').trigger('click');
        }
    });

    jQuery(document).on('click', '.psyemShowCatCont', function (ev) {
        var psyemCatCont = jQuery(this);
        var termId = psyemCatCont.attr('data-term');
        if (termId > 0) {
            jQuery('.search_cat').val(termId);
            jQuery('.psyemPostListSearchForm').submit();
        }
    });

    jQuery(document).on('click', '.psyemShowAllCats', function (ev) {
        jQuery('.search_cat').val('');
        jQuery('.search-text').val('');
        jQuery('.psyemPostListSearchForm').submit();
    });
});

window.onload = function () {
    var psyemListingCont = document.querySelector('.psyemListingCont');
    if (psyemListingCont) {
        psyemListingCont.style.display = 'block';
    }
};
