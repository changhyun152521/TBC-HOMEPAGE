<?php
/**
 * TBC 관리자 좌측 메뉴를 항상 펼친 상태로 유지
 */
if (!defined('_GNUBOARD_')) {
    exit;
}

if (!function_exists('tbc_admin_layout_menu_pinned')) {
    add_event('admin_common', 'tbc_admin_layout_menu_pinned', G5_HOOK_DEFAULT_PRIORITY, 0);

    function tbc_admin_layout_menu_pinned()
    {
        if (!defined('G5_IS_ADMIN') || !G5_IS_ADMIN) {
            return;
        }

        add_javascript('<script>
jQuery(function($) {
    var $gnb = $("#gnb");
    var $container = $("#container");
    var $btnGnb = $("#btn_gnb");

    $container.removeClass("container-small");
    $gnb.removeClass("gnb_small");
    $btnGnb.removeClass("btn_gnb_open");

    if (!$gnb.find(".gnb_li.on").length) {
        $gnb.find(".gnb_li").first().addClass("on");
    }

    try {
        delete_cookie("g5_admin_btn_gnb");
    } catch (err) {}

    $btnGnb.off("click").on("click", function(e) {
        e.preventDefault();
        $container.removeClass("container-small");
        $gnb.removeClass("gnb_small");
        $btnGnb.removeClass("btn_gnb_open");
        if (!$gnb.find(".gnb_li.on").length) {
            $gnb.find(".gnb_li").first().addClass("on");
        }
    });
});
</script>', 99);
    }
}
