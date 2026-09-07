<?php
/**
 * TBC 그누보드 테마 — URL·메뉴 헬퍼
 */
if (!defined('_GNUBOARD_')) exit;

function tbc_page_url($page)
{
    return G5_BBS_URL . '/page.php?p=' . urlencode($page);
}

function tbc_board_url($bo_table)
{
    return G5_BBS_URL . '/board.php?bo_table=' . urlencode($bo_table);
}

function tbc_theme_url($path = '')
{
    return G5_THEME_URL . ($path ? '/' . ltrim($path, '/') : '');
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
    $map = array(
        'greeting' => 'sub about',
        'philosophy' => 'sub about',
        'history' => 'sub about',
        'academies' => 'sub',
        'academies_main' => 'sub',
        'academies_branch' => 'sub',
        'teachers' => 'sub',
        'schedule' => 'sub',
    );
    return isset($map[$p]) ? $map[$p] : 'sub';
}
