<?php
/**
 * TBC 그누보드 테마 — URL·메뉴 헬퍼
 */
if (!defined('_GNUBOARD_')) exit;

if (!defined('TBC_SITE_TITLE')) {
    define('TBC_SITE_TITLE', 'TBC 홈페이지 테스트용');
}

function tbc_site_title($page_title = '')
{
    $page_title = trim((string) $page_title);

    if ($page_title === '' || preg_match('/mycafe24\.com/i', $page_title)) {
        return TBC_SITE_TITLE;
    }

    if ($page_title === TBC_SITE_TITLE) {
        return TBC_SITE_TITLE;
    }

    return $page_title . ' | ' . TBC_SITE_TITLE;
}

function tbc_page_url($page)
{
    return G5_BBS_URL . '/page.php?p=' . urlencode($page);
}

function tbc_board_url($bo_table)
{
    if ($bo_table === 'notice') {
        return tbc_page_url('notice');
    }
    if ($bo_table === 'edu') {
        return tbc_page_url('edu');
    }
    if ($bo_table === 'consult') {
        return tbc_page_url('consult');
    }

    return G5_BBS_URL . '/board.php?bo_table=' . urlencode($bo_table);
}

function tbc_theme_url($path = '')
{
    return G5_THEME_URL . ($path ? '/' . ltrim($path, '/') : '');
}

function tbc_media_path($group)
{
    $map = array(
        'teacher' => G5_DATA_PATH . '/tbc/teacher',
        'greeting' => G5_DATA_PATH . '/tbc/greeting',
        'banner' => G5_DATA_PATH . '/tbc/banner',
        'academy' => G5_DATA_PATH . '/tbc/academy',
        'schedule' => G5_DATA_PATH . '/tbc/schedule',
        'menu' => G5_DATA_PATH . '/tbc/menu',
        'notice' => G5_DATA_PATH . '/tbc/notice',
        'edu' => G5_DATA_PATH . '/tbc/edu',
        'admission' => G5_DATA_PATH . '/tbc/admission',
    );

    return isset($map[$group]) ? $map[$group] : '';
}

function tbc_media_url($group, $filename)
{
    if (!$filename) {
        return '';
    }

    return G5_BBS_URL . '/tbc_media.php?group=' . urlencode($group) . '&name=' . urlencode($filename);
}

function tbc_media_resolve_url($group, $filename, $theme_fallback = '')
{
    if (!$filename) {
        return $theme_fallback;
    }

    $path = tbc_media_path($group);
    if ($path && is_file($path . '/' . $filename)) {
        return tbc_media_url($group, $filename);
    }

    return $theme_fallback;
}

function tbc_is_page($page)
{
    return isset($_GET['p']) && $_GET['p'] === $page;
}

function tbc_body_class()
{
    if (defined('_INDEX_')) {
        return 'main';
    }
    $p = isset($_GET['p']) ? $_GET['p'] : '';

    $about = array('greeting', 'philosophy', 'history');
    if (in_array($p, $about, true)) {
        return 'sub about';
    }
    if ($p === 'teachers' || strpos($p, 'teachers_') === 0) {
        return 'sub teachers';
    }
    if ($p === 'academies' || strpos($p, 'academies_') === 0) {
        return 'sub academies';
    }
    if ($p === 'schedule' || strpos($p, 'schedule_') === 0) {
        return 'sub schedule';
    }
    if ($p === 'notice' || $p === 'notice_view') {
        return 'sub notice';
    }
    if ($p === 'edu' || $p === 'edu_view') {
        return 'sub edu';
    }
    if ($p === 'admission' || $p === 'faq') {
        return 'sub admission';
    }
    if ($p === 'consult') {
        return 'sub admission';
    }

    return 'sub';
}
