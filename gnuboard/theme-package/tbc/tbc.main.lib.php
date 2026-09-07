<?php
/**
 * TBC 메인 배너·문구 관리
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_media_url')) {
    include_once(G5_THEME_PATH . '/tbc.lib.php');
}

define('TBC_MAIN_CONFIG_ID', 1);

function tbc_main_config_table()
{
    return G5_TABLE_PREFIX . 'tbc_main_config';
}

function tbc_main_banner_table()
{
    return G5_TABLE_PREFIX . 'tbc_banner';
}

function tbc_main_banner_path()
{
    return G5_DATA_PATH . '/tbc/banner';
}

function tbc_main_banner_url($filename)
{
    return tbc_media_url('banner', $filename);
}

function tbc_main_ensure_banner_dir()
{
    $banner_dir = tbc_main_banner_path();
    if (!is_dir($banner_dir)) {
        @mkdir($banner_dir, G5_DIR_PERMISSION, true);
        @chmod($banner_dir, G5_DIR_PERMISSION);
    }

    $index_file = $banner_dir . '/index.php';
    if (!is_file($index_file)) {
        @file_put_contents($index_file, '');
        @chmod($index_file, G5_FILE_PERMISSION);
    }
}

function tbc_main_tables_exist()
{
    $config = sql_fetch(" show tables like '" . tbc_main_config_table() . "' ");
    $banner = sql_fetch(" show tables like '" . tbc_main_banner_table() . "' ");
    return $config && $banner;
}

function tbc_main_column_exists($table, $column)
{
    $row = sql_fetch(" show columns from `{$table}` like '{$column}' ");
    return (bool) $row;
}

function tbc_main_upgrade_schema()
{
    if (!tbc_main_tables_exist()) {
        return;
    }

    $table = tbc_main_config_table();
    $defaults = tbc_main_default_config();

    foreach ($defaults as $column => $default_value) {
        if (!tbc_main_column_exists($table, $column)) {
            sql_query(" alter table `{$table}` add `{$column}` text not null ", false);
        }
    }

    tbc_main_fill_empty_config_columns();
}

function tbc_main_fill_empty_config_columns()
{
    $defaults = tbc_main_default_config();
    $table = tbc_main_config_table();
    $row = sql_fetch(" select * from `{$table}` where id = " . TBC_MAIN_CONFIG_ID);
    if (!$row) {
        return;
    }

    foreach ($defaults as $key => $default) {
        if (!array_key_exists($key, $row) || $row[$key] === '') {
            $value = sql_real_escape_string($default);
            sql_query(" update `{$table}` set `{$key}` = '{$value}' where id = " . TBC_MAIN_CONFIG_ID);
        }
    }
}

function tbc_main_ensure_tables()
{
    if (tbc_main_tables_exist()) {
        tbc_main_upgrade_schema();
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_main_config_table() . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `main_title` text NOT NULL,
            `main_subtitle` text NOT NULL,
            `right_tagline` text NOT NULL,
            `right_title` text NOT NULL,
            `section01_tagline` text NOT NULL,
            `section01_title` text NOT NULL,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_main_banner_table() . "` (
            `bn_id` int(11) NOT NULL AUTO_INCREMENT,
            `bn_image` varchar(255) NOT NULL DEFAULT '',
            `bn_order` int(11) NOT NULL DEFAULT 0,
            `bn_use` tinyint(4) NOT NULL DEFAULT 1,
            PRIMARY KEY (`bn_id`),
            KEY `bn_order` (`bn_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    tbc_main_upgrade_schema();
    return tbc_main_tables_exist();
}

function tbc_main_default_config()
{
    return array(
        'main_title' => "대전·세종을 대표하는\n교육 브랜드 더브레인코어",
        'main_subtitle' => '교육철학과 전문관 시스템으로 학생의 성장을 함께합니다.',
        'right_tagline' => '하나의 브랜드, 여러 전문관',
        'right_title' => "대전·세종을 대표하는\n교육 브랜드 더브레인코어",
        'section01_tagline' => '하나의 교육 브랜드, 더브레인코어',
        'section01_title' => "전문관과 분원이 연결된\n대전·세종 대표 교육 브랜드",
        'section02_heading' => "체계적인 교육과정과\n전문관 시스템으로\n학생의 성장을 함께합니다",
        'section02_card1' => "초등부터 고등까지 !\n전문관별 맞춤\n교육과정",
        'section02_card2' => "더브레인코어와 함께\n성장의 여정을\n시작해보세요.",
        'section02_card3' => "학생별 맞춤 학습과\n체계적인 관리 시스템",
    );
}

function tbc_main_admin_field_groups()
{
    return array(
        array(
            'step' => '①',
            'title' => '최상단 — 배너 왼쪽 문구',
            'desc' => '메인 최상단 배너 슬라이드 위에 겹쳐 보이는 글입니다.',
            'fields' => array(
                array('key' => 'main_title', 'label' => '큰 제목', 'rows' => 3),
                array('key' => 'main_subtitle', 'label' => '설명 문구', 'rows' => 2),
            ),
        ),
        array(
            'step' => '②',
            'title' => '최상단 — 배너 오른쪽 박스',
            'desc' => '배너 오른쪽 「하나의 브랜드, 여러 전문관」 영역입니다.',
            'fields' => array(
                array('key' => 'right_tagline', 'label' => '한 줄 문구 (전구 아이콘 옆)', 'rows' => 1, 'single_line' => true),
                array('key' => 'right_title', 'label' => '제목', 'rows' => 3),
            ),
        ),
        array(
            'step' => '③',
            'title' => '중간 — 강사진 위 「하나의 교육 브랜드」',
            'desc' => '강사진 사진이 나오기 전, 캐릭터 이미지 옆 문구입니다.',
            'fields' => array(
                array('key' => 'section01_tagline', 'label' => '한 줄 문구', 'rows' => 1, 'single_line' => true),
                array('key' => 'section01_title', 'label' => '큰 제목', 'rows' => 3),
            ),
        ),
        array(
            'step' => '④',
            'title' => '중간 — 교육과정 소개 (캐릭터 섹션)',
            'desc' => '캐릭터 이미지가 있는 교육과정 안내 영역입니다.',
            'fields' => array(
                array('key' => 'section02_heading', 'label' => '섹션 큰 제목 (캐릭터 옆)', 'rows' => 4),
                array('key' => 'section02_card1', 'label' => '왼쪽 카드 문구', 'rows' => 3),
                array('key' => 'section02_card2', 'label' => '오른쪽 위 카드 문구', 'rows' => 3),
                array('key' => 'section02_card3', 'label' => '오른쪽 아래 카드 문구', 'rows' => 3),
            ),
        ),
    );
}

function tbc_main_seed_defaults()
{
    if (!tbc_main_ensure_tables()) {
        return false;
    }

    tbc_main_ensure_banner_dir();

    $defaults = tbc_main_default_config();
    $config = sql_fetch(" select id from " . tbc_main_config_table() . " where id = " . TBC_MAIN_CONFIG_ID);
    if (!$config) {
        $sets = array("id = " . TBC_MAIN_CONFIG_ID);
        foreach ($defaults as $key => $value) {
            $sets[] = "`{$key}` = '" . sql_real_escape_string($value) . "'";
        }
        $sets[] = "updated_at = '" . G5_TIME_YMDHIS . "'";
        sql_query(" insert into " . tbc_main_config_table() . " set " . implode(', ', $sets));
    } else {
        tbc_main_fill_empty_config_columns();
    }

    $count = sql_fetch(" select count(*) as cnt from " . tbc_main_banner_table() . " ");
    if ((int) $count['cnt'] > 0) {
        return true;
    }

    $theme_images = array('main_banner_01.jpg', 'main_banner_02.jpg', 'main_banner_03.jpg');
    $order = 1;
    foreach ($theme_images as $source_name) {
        $source = G5_THEME_PATH . '/img/main/' . $source_name;
        sql_query("
            insert into " . tbc_main_banner_table() . "
            set bn_image = '',
                bn_order = {$order},
                bn_use = 1
        ");
        $bn_id = sql_insert_id();
        if (!$bn_id) {
            continue;
        }

        $ext = tbc_main_allowed_image_ext($source_name);
        if (!$ext) {
            $ext = 'jpg';
        }
        $dest_name = 'banner_' . $bn_id . '.' . $ext;
        $dest = tbc_main_banner_path() . '/' . $dest_name;

        if (is_file($source)) {
            @copy($source, $dest);
            @chmod($dest, G5_FILE_PERMISSION);
        }

        if (is_file($dest)) {
            $image = sql_real_escape_string($dest_name);
            sql_query(" update " . tbc_main_banner_table() . " set bn_image = '{$image}' where bn_id = {$bn_id} ");
        }

        $order++;
    }

    return true;
}

function tbc_main_get_config()
{
    $defaults = tbc_main_default_config();

    if (!tbc_main_tables_exist()) {
        return $defaults;
    }

    tbc_main_upgrade_schema();

    $columns = implode(', ', array_keys($defaults));
    $row = sql_fetch(" select {$columns} from " . tbc_main_config_table() . " where id = " . TBC_MAIN_CONFIG_ID);
    if (!$row) {
        return $defaults;
    }

    $config = array();
    foreach ($defaults as $key => $default) {
        $config[$key] = isset($row[$key]) && $row[$key] !== '' ? $row[$key] : $default;
    }

    return $config;
}

function tbc_main_banner_row_url($row)
{
    if (!$row['bn_image']) {
        return '';
    }

    $path = tbc_main_banner_path() . '/' . $row['bn_image'];
    if (is_file($path)) {
        return tbc_main_banner_url($row['bn_image']);
    }

    return G5_THEME_URL . '/img/main/main_banner_01.jpg';
}

function tbc_main_get_banners()
{
    $fallback = array();
    for ($i = 1; $i <= 3; $i++) {
        $fallback[] = array(
            'bn_id' => $i,
            'bn_image' => '',
            'bn_order' => $i,
            'bn_use' => 1,
            'image_url' => G5_THEME_URL . '/img/main/main_banner_0' . $i . '.jpg',
        );
    }

    if (!tbc_main_tables_exist()) {
        return $fallback;
    }

    $result = sql_query("
        select bn_id, bn_image, bn_order, bn_use
        from " . tbc_main_banner_table() . "
        where bn_use = 1 and bn_image != ''
        order by bn_order asc, bn_id asc
    ");

    $banners = array();
    while ($row = sql_fetch_array($result)) {
        $row['image_url'] = tbc_main_banner_row_url($row);
        if (!$row['image_url']) {
            continue;
        }
        $banners[] = $row;
    }

    return $banners ? $banners : $fallback;
}

function tbc_main_get_all_banners()
{
    if (!tbc_main_tables_exist()) {
        return array();
    }

    $result = sql_query("
        select bn_id, bn_image, bn_order, bn_use
        from " . tbc_main_banner_table() . "
        order by bn_order asc, bn_id asc
    ");

    $banners = array();
    while ($row = sql_fetch_array($result)) {
        $row['image_url'] = tbc_main_banner_row_url($row);
        $banners[] = $row;
    }

    return $banners;
}

function tbc_main_save_config($fields)
{
    tbc_main_ensure_tables();

    $defaults = tbc_main_default_config();
    $sets = array();

    foreach ($defaults as $key => $default) {
        if (!isset($fields[$key])) {
            continue;
        }
        $sets[] = "`{$key}` = '" . sql_real_escape_string(trim($fields[$key])) . "'";
    }

    if (!$sets) {
        return;
    }

    $sets[] = "updated_at = '" . G5_TIME_YMDHIS . "'";

    $exists = sql_fetch(" select id from " . tbc_main_config_table() . " where id = " . TBC_MAIN_CONFIG_ID);
    if ($exists) {
        sql_query(" update " . tbc_main_config_table() . " set " . implode(', ', $sets) . " where id = " . TBC_MAIN_CONFIG_ID);
        return;
    }

    $insert_sets = array("id = " . TBC_MAIN_CONFIG_ID);
    foreach ($defaults as $key => $default) {
        $value = isset($fields[$key]) ? trim($fields[$key]) : $default;
        $insert_sets[] = "`{$key}` = '" . sql_real_escape_string($value) . "'";
    }
    $insert_sets[] = "updated_at = '" . G5_TIME_YMDHIS . "'";
    sql_query(" insert into " . tbc_main_config_table() . " set " . implode(', ', $insert_sets));
}

function tbc_main_allowed_image_ext($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, array('jpg', 'jpeg', 'png', 'webp', 'gif'), true) ? $ext : '';
}

function tbc_main_validate_upload_file($file)
{
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return array('ok' => false, 'message' => '업로드된 파일이 없습니다.');
    }

    if (!empty($file['error'])) {
        return array('ok' => false, 'message' => '파일 업로드 오류가 발생했습니다.');
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return array('ok' => false, 'message' => '이미지는 5MB 이하만 업로드할 수 있습니다.');
    }

    $ext = tbc_main_allowed_image_ext($file['name']);
    if (!$ext) {
        return array('ok' => false, 'message' => 'jpg, png, webp, gif 파일만 업로드할 수 있습니다.');
    }

    return array('ok' => true, 'ext' => $ext);
}

function tbc_main_store_banner_file($bn_id, $file)
{
    $check = tbc_main_validate_upload_file($file);
    if (!$check['ok']) {
        return $check;
    }

    tbc_main_ensure_banner_dir();

    $filename = 'banner_' . (int) $bn_id . '.' . $check['ext'];
    $dest = tbc_main_banner_path() . '/' . $filename;

    $row = sql_fetch(" select bn_image from " . tbc_main_banner_table() . " where bn_id = " . (int) $bn_id);
    if ($row && $row['bn_image'] && $row['bn_image'] !== $filename) {
        $old = tbc_main_banner_path() . '/' . $row['bn_image'];
        if (is_file($old)) {
            @unlink($old);
        }
    }

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return array('ok' => false, 'message' => '파일 저장에 실패했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    $filename_esc = sql_real_escape_string($filename);
    sql_query("
        update " . tbc_main_banner_table() . "
        set bn_image = '{$filename_esc}',
            bn_use = 1
        where bn_id = " . (int) $bn_id . "
    ");

    return array('ok' => true, 'filename' => $filename);
}

function tbc_main_add_banner($file)
{
    tbc_main_ensure_tables();

    $check = tbc_main_validate_upload_file($file);
    if (!$check['ok']) {
        return $check;
    }

    $max = sql_fetch(" select ifnull(max(bn_order), 0) as max_order from " . tbc_main_banner_table() . " ");
    $order = (int) $max['max_order'] + 1;

    sql_query("
        insert into " . tbc_main_banner_table() . "
        set bn_image = '',
            bn_order = {$order},
            bn_use = 1
    ");

    $bn_id = sql_insert_id();
    if (!$bn_id) {
        return array('ok' => false, 'message' => '배너 등록에 실패했습니다.');
    }

    return tbc_main_store_banner_file($bn_id, $file);
}

function tbc_main_update_banner_image($bn_id, $file)
{
    tbc_main_ensure_tables();

    $row = sql_fetch(" select bn_id from " . tbc_main_banner_table() . " where bn_id = " . (int) $bn_id);
    if (!$row) {
        return array('ok' => false, 'message' => '배너를 찾을 수 없습니다.');
    }

    return tbc_main_store_banner_file($bn_id, $file);
}

function tbc_main_delete_banner($bn_id)
{
    tbc_main_ensure_tables();

    $bn_id = (int) $bn_id;
    $row = sql_fetch(" select bn_image from " . tbc_main_banner_table() . " where bn_id = {$bn_id} ");
    if (!$row) {
        return array('ok' => false, 'message' => '배너를 찾을 수 없습니다.');
    }

    if ($row['bn_image']) {
        $path = tbc_main_banner_path() . '/' . $row['bn_image'];
        if (is_file($path)) {
            @unlink($path);
        }
    }

    sql_query(" delete from " . tbc_main_banner_table() . " where bn_id = {$bn_id} ");

    return array('ok' => true);
}

function tbc_main_save_banner_orders($orders)
{
    tbc_main_ensure_tables();

    if (!is_array($orders)) {
        return;
    }

    foreach ($orders as $bn_id => $order) {
        $bn_id = (int) $bn_id;
        $order = (int) $order;
        if ($bn_id < 1) {
            continue;
        }
        sql_query(" update " . tbc_main_banner_table() . " set bn_order = {$order} where bn_id = {$bn_id} ");
    }
}

function tbc_main_normalize_banner_orders()
{
    $banners = tbc_main_get_all_banners();
    $order = 1;
    foreach ($banners as $banner) {
        sql_query(" update " . tbc_main_banner_table() . " set bn_order = {$order} where bn_id = " . (int) $banner['bn_id'] . " ");
        $order++;
    }
}

function tbc_main_move_banner($bn_id, $direction)
{
    tbc_main_ensure_tables();

    $bn_id = (int) $bn_id;
    $banners = tbc_main_get_all_banners();
    if (!$banners) {
        return array('ok' => false, 'message' => '배너가 없습니다.');
    }

    $index = -1;
    foreach ($banners as $i => $banner) {
        if ((int) $banner['bn_id'] === $bn_id) {
            $index = $i;
            break;
        }
    }

    if ($index < 0) {
        return array('ok' => false, 'message' => '배너를 찾을 수 없습니다.');
    }

    if ($direction === 'up') {
        if ($index === 0) {
            return array('ok' => false, 'message' => '이미 맨 위입니다.');
        }
        $swap = $index - 1;
    } elseif ($direction === 'down') {
        if ($index === count($banners) - 1) {
            return array('ok' => false, 'message' => '이미 맨 아래입니다.');
        }
        $swap = $index + 1;
    } else {
        return array('ok' => false, 'message' => '잘못된 요청입니다.');
    }

    $current_order = (int) $banners[$index]['bn_order'];
    $target_order = (int) $banners[$swap]['bn_order'];

    sql_query(" update " . tbc_main_banner_table() . " set bn_order = {$target_order} where bn_id = {$bn_id} ");
    sql_query(" update " . tbc_main_banner_table() . " set bn_order = {$current_order} where bn_id = " . (int) $banners[$swap]['bn_id'] . " ");

    tbc_main_normalize_banner_orders();

    return array('ok' => true, 'banners' => tbc_main_format_banners_for_admin());
}

function tbc_main_delete_banner_and_normalize($bn_id)
{
    $result = tbc_main_delete_banner($bn_id);
    if (!$result['ok']) {
        return $result;
    }

    tbc_main_normalize_banner_orders();

    return array('ok' => true, 'banners' => tbc_main_format_banners_for_admin());
}

function tbc_main_format_banners_for_admin()
{
    $banners = tbc_main_get_all_banners();
    $items = array();

    foreach ($banners as $i => $banner) {
        $preview = $banner['image_url'] ? $banner['image_url'] : G5_THEME_URL . '/img/main/main_banner_01.jpg';
        $items[] = array(
            'bn_id' => (int) $banner['bn_id'],
            'bn_order' => (int) $banner['bn_order'],
            'image_url' => $preview,
            'position' => $i + 1,
        );
    }

    return $items;
}

function tbc_main_format_title_html($title)
{
    return nl2br(htmlspecialchars($title, ENT_QUOTES, 'UTF-8'));
}

function tbc_main_format_preline_text($title)
{
    return htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
}
