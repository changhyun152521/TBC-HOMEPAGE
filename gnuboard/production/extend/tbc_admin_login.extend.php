<?php
/**
 * 관리자 비로그인 접근 시 TBC 브랜드 로그인 화면으로 이동
 */
if (!defined('_GNUBOARD_')) {
    exit;
}

if (!function_exists('tbc_admin_login_gate')) {
    function tbc_admin_login_gate()
    {
        if (!defined('G5_IS_ADMIN') || !G5_IS_ADMIN) {
            return;
        }

        global $member;

        if (!empty($member['mb_id'])) {
            return;
        }

        $script = basename($_SERVER['SCRIPT_NAME']);
        if ($script === 'tbc_login.php') {
            return;
        }

        $return_url = G5_ADMIN_URL;
        if (!empty($_SERVER['REQUEST_URI'])) {
            $return_url = G5_ADMIN_URL . str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            if (strpos($return_url, '?') === false && !empty($_SERVER['QUERY_STRING'])) {
                $return_url .= '?' . $_SERVER['QUERY_STRING'];
            }
        }

        if (function_exists('correct_goto_url')) {
            $return_url = correct_goto_url($return_url);
        }

        goto_url(G5_ADMIN_URL . '/tbc_login.php?url=' . urlencode($return_url));
    }

    tbc_admin_login_gate();
}

if (!function_exists('tbc_admin_login_page_bridge')) {
    add_event('common_header', 'tbc_admin_login_page_bridge', 1, 0);

    function tbc_admin_login_page_bridge()
    {
        if (defined('G5_IS_ADMIN') && G5_IS_ADMIN) {
            return;
        }

        $script = basename($_SERVER['SCRIPT_NAME']);
        if ($script !== 'login.php') {
            return;
        }

        $url = isset($_GET['url']) ? $_GET['url'] : '';
        if (!$url || strpos($url, G5_ADMIN_URL) === false) {
            return;
        }

        $query = 'url=' . urlencode($url);
        if (!empty($_GET['msg'])) {
            $query .= '&msg=' . urlencode(strip_tags($_GET['msg']));
        }

        goto_url(G5_ADMIN_URL . '/tbc_login.php?' . $query);
    }
}
