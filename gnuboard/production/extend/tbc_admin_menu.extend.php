<?php
/**
 * TBC 전용 부관리자(tbc) 좌측 메뉴를 TBC 관리만 표시
 */
if (!defined('_GNUBOARD_')) {
    exit;
}

if (!defined('TBC_ADMIN_ONLY_IDS')) {
    define('TBC_ADMIN_ONLY_IDS', 'tbc');
}

function tbc_admin_only_ids()
{
    $ids = array_map('trim', explode(',', TBC_ADMIN_ONLY_IDS));
    return array_values(array_filter($ids));
}

function tbc_is_tbc_only_admin()
{
    global $member, $is_admin;

    if ($is_admin === 'super' || empty($member['mb_id'])) {
        return false;
    }

    return in_array($member['mb_id'], tbc_admin_only_ids(), true);
}

if (!function_exists('tbc_filter_admin_amenu')) {
    add_replace('admin_amenu', 'tbc_filter_admin_amenu', G5_HOOK_DEFAULT_PRIORITY, 1);
    add_replace('admin_menu', 'tbc_filter_admin_menu', G5_HOOK_DEFAULT_PRIORITY, 1);
    add_event('admin_common', 'tbc_admin_only_bootstrap', G5_HOOK_DEFAULT_PRIORITY, 0);

    function tbc_filter_admin_amenu($amenu)
    {
        if (!tbc_is_tbc_only_admin()) {
            return $amenu;
        }

        if (!isset($amenu['950'])) {
            return $amenu;
        }

        return array('950' => $amenu['950']);
    }

    function tbc_filter_admin_menu($menu)
    {
        if (!tbc_is_tbc_only_admin()) {
            return $menu;
        }

        if (!isset($menu['menu950'])) {
            return $menu;
        }

        return array('menu950' => $menu['menu950']);
    }

    function tbc_admin_only_bootstrap()
    {
        if (!tbc_is_tbc_only_admin()) {
            return;
        }

        $script = basename($_SERVER['SCRIPT_NAME']);

        if ($script === 'index.php') {
            goto_url(G5_ADMIN_URL . '/tbc_main.php');
        }
    }
}
