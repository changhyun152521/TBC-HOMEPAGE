<?php
/**
 * TBC 메뉴 이미지 관리 (전체메뉴 패널 · 서브 상단 배너)
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_media_url')) {
    include_once(G5_THEME_PATH . '/tbc.lib.php');
}

function tbc_menu_table()
{
    return G5_TABLE_PREFIX . 'tbc_menu_image';
}

function tbc_menu_image_path()
{
    return G5_DATA_PATH . '/tbc/menu';
}

function tbc_menu_image_url($filename)
{
    return tbc_media_url('menu', $filename);
}

function tbc_menu_definitions()
{
    return array(
        'default' => array(
            'label' => '전체메뉴 기본',
            'panel_fallback' => 'img/common/all_bg00.jpg',
            'sub_fallback' => 'img/sub/bg_10.jpg',
            'panel_index' => 0,
        ),
        'about' => array(
            'label' => '더브코',
            'panel_fallback' => 'img/common/all_bg01.jpg',
            'sub_fallback' => 'img/sub/bg_10.jpg',
            'panel_index' => 1,
        ),
        'academies' => array(
            'label' => '분원',
            'panel_fallback' => 'img/common/all_bg02.jpg',
            'sub_fallback' => 'img/sub/bg_10.jpg',
            'panel_index' => 2,
        ),
        'teachers' => array(
            'label' => '강사진',
            'panel_fallback' => 'img/common/all_bg03.jpg',
            'sub_fallback' => 'img/sub/bg_10.jpg',
            'panel_index' => 3,
        ),
        'schedule' => array(
            'label' => '시간표',
            'panel_fallback' => 'img/common/all_bg04.jpg',
            'sub_fallback' => 'img/sub/bg_10.jpg',
            'panel_index' => 4,
        ),
        'news' => array(
            'label' => '더브코 소식',
            'panel_fallback' => 'img/common/all_bg05.jpg',
            'sub_fallback' => 'img/sub/bg_10.jpg',
            'panel_index' => 5,
        ),
        'admission' => array(
            'label' => '입학안내',
            'panel_fallback' => 'img/common/all_bg05.jpg',
            'sub_fallback' => 'img/sub/bg_10.jpg',
            'panel_index' => 6,
        ),
    );
}

function tbc_menu_ensure_image_dir()
{
    $dir = tbc_menu_image_path();
    if (!is_dir($dir)) {
        @mkdir($dir, G5_DIR_PERMISSION, true);
        @chmod($dir, G5_DIR_PERMISSION);
    }

    $index_file = $dir . '/index.php';
    if (!is_file($index_file)) {
        @file_put_contents($index_file, '');
        @chmod($index_file, G5_FILE_PERMISSION);
    }
}

function tbc_menu_table_exists()
{
    $row = sql_fetch(" show tables like '" . tbc_menu_table() . "' ");
    return (bool) $row;
}

function tbc_menu_ensure_tables()
{
    if (tbc_menu_table_exists()) {
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_menu_table() . "` (
            `mn_key` varchar(20) NOT NULL,
            `mn_label` varchar(80) NOT NULL DEFAULT '',
            `mn_panel_image` varchar(255) NOT NULL DEFAULT '',
            `mn_sub_banner_image` varchar(255) NOT NULL DEFAULT '',
            `mn_sort` int(11) NOT NULL DEFAULT 0,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`mn_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    return tbc_menu_table_exists();
}

function tbc_menu_seed_defaults()
{
    if (!tbc_menu_ensure_tables()) {
        return false;
    }

    $defs = tbc_menu_definitions();
    $sort = 0;

    foreach ($defs as $key => $meta) {
        $exists = sql_fetch(" select mn_key from `" . tbc_menu_table() . "` where mn_key = '" . sql_real_escape_string($key) . "' ");
        if ($exists) {
            continue;
        }

        $label = sql_real_escape_string($meta['label']);
        sql_query("
            insert into `" . tbc_menu_table() . "` set
                mn_key = '" . sql_real_escape_string($key) . "',
                mn_label = '{$label}',
                mn_panel_image = '',
                mn_sub_banner_image = '',
                mn_sort = '" . (int) $sort . "',
                updated_at = '" . G5_TIME_YMDHIS . "'
        ");
        $sort++;
    }

    return true;
}

function tbc_menu_get_rows()
{
    tbc_menu_ensure_tables();
    tbc_menu_seed_defaults();

    $rows = array();
    $result = sql_query(" select * from `" . tbc_menu_table() . "` order by mn_sort asc, mn_key asc ");
    while ($row = sql_fetch_array($result)) {
        $rows[$row['mn_key']] = $row;
    }

    $ordered = array();
    foreach (tbc_menu_definitions() as $key => $meta) {
        if (isset($rows[$key])) {
            $ordered[$key] = $rows[$key];
        }
    }

    return $ordered;
}

function tbc_menu_theme_fallback($path)
{
    return G5_THEME_URL . '/' . ltrim($path, '/');
}

function tbc_menu_resolve_image_url($filename, $fallback_path)
{
    return tbc_media_resolve_url('menu', $filename, tbc_menu_theme_fallback($fallback_path));
}

function tbc_menu_panel_url($key)
{
    $defs = tbc_menu_definitions();
    if (!isset($defs[$key])) {
        return tbc_menu_theme_fallback('img/common/all_bg00.jpg');
    }

    $rows = tbc_menu_get_rows();
    $row = isset($rows[$key]) ? $rows[$key] : null;
    $filename = $row ? $row['mn_panel_image'] : '';

    return tbc_menu_resolve_image_url($filename, $defs[$key]['panel_fallback']);
}

function tbc_menu_sub_banner_url($key = '')
{
    if ($key === '') {
        $key = tbc_menu_current_key();
    }

    $defs = tbc_menu_definitions();
    if (!isset($defs[$key])) {
        $key = 'about';
    }

    $rows = tbc_menu_get_rows();
    $row = isset($rows[$key]) ? $rows[$key] : null;
    $filename = $row ? $row['mn_sub_banner_image'] : '';

    return tbc_menu_resolve_image_url($filename, $defs[$key]['sub_fallback']);
}

function tbc_menu_panel_map()
{
    $map = array('default' => tbc_menu_panel_url('default'));

    foreach (tbc_menu_definitions() as $key => $meta) {
        if ($key === 'default') {
            continue;
        }
        $map[$key] = tbc_menu_panel_url($key);
    }

    return $map;
}

function tbc_menu_current_key()
{
    if (defined('_INDEX_')) {
        return '';
    }

    $p = isset($_GET['p']) ? $_GET['p'] : '';
    if (in_array($p, array('greeting', 'philosophy', 'history'), true)) {
        return 'about';
    }
    if ($p === 'academies' || strpos($p, 'academies_') === 0) {
        return 'academies';
    }
    if ($p === 'teachers' || strpos($p, 'teachers_') === 0) {
        return 'teachers';
    }
    if ($p === 'schedule' || strpos($p, 'schedule_') === 0) {
        return 'schedule';
    }
    if (in_array($p, array('admission', 'faq', 'consult'), true)) {
        return 'admission';
    }
    if ($p === 'notice' || $p === 'notice_view') {
        return 'news';
    }

    $bo = isset($_GET['bo_table']) ? $_GET['bo_table'] : '';
    if (in_array($bo, array('notice', 'edu', 'review'), true)) {
        return 'news';
    }
    if ($bo === 'consult') {
        return 'admission';
    }

    return 'about';
}

function tbc_menu_allowed_image_ext($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    return in_array($ext, $allowed, true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : '';
}

function tbc_menu_validate_upload_file($file)
{
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return array('ok' => false, 'message' => '업로드된 파일이 없습니다.');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return array('ok' => false, 'message' => '파일 업로드 중 오류가 발생했습니다.');
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return array('ok' => false, 'message' => '파일 크기는 5MB 이하여야 합니다.');
    }

    $ext = tbc_menu_allowed_image_ext($file['name']);
    if (!$ext) {
        return array('ok' => false, 'message' => 'jpg, png, webp, gif 파일만 업로드할 수 있습니다.');
    }

    return array('ok' => true, 'ext' => $ext);
}

function tbc_menu_delete_file($filename)
{
    if (!$filename) {
        return;
    }

    $path = tbc_menu_image_path() . '/' . $filename;
    if (is_file($path)) {
        @unlink($path);
    }
}

function tbc_menu_update_image($key, $type, $file)
{
    tbc_menu_ensure_tables();
    tbc_menu_seed_defaults();

    $defs = tbc_menu_definitions();
    if (!isset($defs[$key])) {
        return array('ok' => false, 'message' => '잘못된 메뉴 키입니다.');
    }

    if ($type !== 'panel' && $type !== 'sub_banner') {
        return array('ok' => false, 'message' => '잘못된 이미지 유형입니다.');
    }

    $check = tbc_menu_validate_upload_file($file);
    if (!$check['ok']) {
        return $check;
    }

    tbc_menu_ensure_image_dir();

    $column = $type === 'panel' ? 'mn_panel_image' : 'mn_sub_banner_image';
    $row = sql_fetch(" select * from `" . tbc_menu_table() . "` where mn_key = '" . sql_real_escape_string($key) . "' ");
    if (!$row) {
        return array('ok' => false, 'message' => '메뉴 정보를 찾을 수 없습니다.');
    }

    $filename = $key . '_' . $type . '_' . date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $check['ext'];
    $dest = tbc_menu_image_path() . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return array('ok' => false, 'message' => '파일 저장에 실패했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    if (!empty($row[$column])) {
        tbc_menu_delete_file($row[$column]);
    }

    $filename_sql = sql_real_escape_string($filename);
    sql_query("
        update `" . tbc_menu_table() . "` set
            `{$column}` = '{$filename_sql}',
            updated_at = '" . G5_TIME_YMDHIS . "'
        where mn_key = '" . sql_real_escape_string($key) . "'
    ");

    return array('ok' => true, 'filename' => $filename);
}
