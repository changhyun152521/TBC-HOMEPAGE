<?php
/**
 * TBC 입학절차 (service1003 디자인)
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_media_url')) {
    include_once(G5_THEME_PATH . '/tbc.lib.php');
}

define('TBC_ADMISSION_CONFIG_ID', 1);

function tbc_admission_config_table()
{
    return G5_TABLE_PREFIX . 'tbc_admission_config';
}

function tbc_admission_step_table()
{
    return G5_TABLE_PREFIX . 'tbc_admission_step';
}

function tbc_admission_point_table()
{
    return G5_TABLE_PREFIX . 'tbc_admission_point';
}

function tbc_admission_flow_table()
{
    return G5_TABLE_PREFIX . 'tbc_admission_flow';
}

function tbc_admission_tables_exist()
{
    $config = sql_fetch(" show tables like '" . tbc_admission_config_table() . "' ");
    $step = sql_fetch(" show tables like '" . tbc_admission_step_table() . "' ");
    $point = sql_fetch(" show tables like '" . tbc_admission_point_table() . "' ");
    $flow = sql_fetch(" show tables like '" . tbc_admission_flow_table() . "' ");
    return (bool) $config && (bool) $step && (bool) $point && (bool) $flow;
}

function tbc_admission_icon_path()
{
    return G5_DATA_PATH . '/tbc/admission';
}

function tbc_admission_icon_url($filename)
{
    return tbc_media_url('admission', $filename);
}

function tbc_admission_default_icon_files()
{
    return array('service1003_icon01.png', 'service1003_icon02.png', 'service1003_icon03.png');
}

function tbc_admission_resolve_icon_url($filename, $index = 0)
{
    $defaults = tbc_admission_default_icon_files();
    $fallback = G5_THEME_URL . '/img/sub/admission/' . $defaults[$index % count($defaults)];

    if (!$filename) {
        return $fallback;
    }

    $path = tbc_admission_icon_path() . '/' . $filename;
    if (is_file($path)) {
        return tbc_admission_icon_url($filename);
    }

    return $fallback;
}

function tbc_admission_ensure_icon_dir()
{
    $dir = tbc_admission_icon_path();
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

function tbc_admission_default_config()
{
    return array(
        'ad_heading' => '입학 절차 안내',
        'ad_test_title' => '레벨 테스트 및 학습 상담',
        'ad_test_highlight' => '개별 학생의 수준, 학습 스타일 및 목표에 맞춰 맞춤형 교육을 제공하기 위해 레벨 테스트와 학습 상담을 진행',
        'ad_test_body' => '합니다. 학생 개개인의 강점과 약점을 파악하여 최적의 학습 방법을 함께 설계하고, 더브레인코어의 체계적인 학습 관리로 성장을 돕습니다.',
    );
}

function tbc_admission_default_steps()
{
    return array(
        array(
            'as_num' => '01',
            'as_text_before' => '전화·온라인으로 ',
            'as_text_em' => '상담 신청',
            'as_text_after' => '을 받습니다.',
            'as_desc' => '더브레인코어 대표번호 또는 홈페이지 상담신청을 통해 편하게 문의하실 수 있습니다.',
        ),
        array(
            'as_num' => '02',
            'as_text_before' => '',
            'as_text_em' => '레벨 테스트와 학습 상담',
            'as_text_after' => '을 진행합니다.',
            'as_desc' => '학생의 현재 수준과 학습 스타일을 파악하고, 맞춤 학습 방향을 함께 설계합니다.',
        ),
        array(
            'as_num' => '03',
            'as_text_before' => '',
            'as_text_em' => '수강 과정 및 시간표',
            'as_text_after' => '를 안내합니다.',
            'as_desc' => '초·중·고 관별·과목별 수업 과정과 본원·분원 운영 정보를 상세히 설명합니다.',
        ),
        array(
            'as_num' => '04',
            'as_text_before' => '',
            'as_text_em' => '등록 완료 후 수업 시작',
            'as_text_after' => '',
            'as_desc' => '등록이 완료되면 담당 선생님과 함께 체계적인 학습을 시작합니다.',
        ),
    );
}

function tbc_admission_default_points()
{
    return array(
        array('ap_label' => 'Point 01', 'ap_text' => "전문 강사진의\n1:1 맞춤 학습 관리"),
        array('ap_label' => 'Point 02', 'ap_text' => "초·중·고 통합\n커리큘럼 운영"),
        array('ap_label' => 'Point 03', 'ap_text' => "레벨별·목표별\n맞춤 수업 편성"),
        array('ap_label' => 'Point 04', 'ap_text' => "본원 6개 전문관·\n5개 분원 통합 브랜드"),
    );
}

function tbc_admission_default_flows()
{
    return array(
        array('af_icon' => '', 'af_label' => '입학 상담 및 테스트 신청'),
        array('af_icon' => '', 'af_label' => '테스트 진행 및 학습 스타일 파악'),
        array('af_icon' => '', 'af_label' => '학습 진도에 맞는 과정 배정'),
    );
}

function tbc_admission_ensure_tables()
{
    if (tbc_admission_tables_exist()) {
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_admission_config_table() . "` (
            `ad_id` int(11) NOT NULL AUTO_INCREMENT,
            `ad_heading` varchar(255) NOT NULL DEFAULT '',
            `ad_test_title` varchar(255) NOT NULL DEFAULT '',
            `ad_test_highlight` text NOT NULL,
            `ad_test_body` text NOT NULL,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`ad_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_admission_step_table() . "` (
            `as_id` int(11) NOT NULL AUTO_INCREMENT,
            `as_num` varchar(4) NOT NULL DEFAULT '',
            `as_text_before` varchar(255) NOT NULL DEFAULT '',
            `as_text_em` varchar(255) NOT NULL DEFAULT '',
            `as_text_after` varchar(255) NOT NULL DEFAULT '',
            `as_desc` text NOT NULL,
            `as_order` int(11) NOT NULL DEFAULT 0,
            `as_use` tinyint(4) NOT NULL DEFAULT 1,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`as_id`),
            KEY `as_order` (`as_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_admission_point_table() . "` (
            `ap_id` int(11) NOT NULL AUTO_INCREMENT,
            `ap_label` varchar(40) NOT NULL DEFAULT '',
            `ap_text` text NOT NULL,
            `ap_order` int(11) NOT NULL DEFAULT 0,
            `ap_use` tinyint(4) NOT NULL DEFAULT 1,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`ap_id`),
            KEY `ap_order` (`ap_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_admission_flow_table() . "` (
            `af_id` int(11) NOT NULL AUTO_INCREMENT,
            `af_icon` varchar(255) NOT NULL DEFAULT '',
            `af_label` varchar(255) NOT NULL DEFAULT '',
            `af_order` int(11) NOT NULL DEFAULT 0,
            `af_use` tinyint(4) NOT NULL DEFAULT 1,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`af_id`),
            KEY `af_order` (`af_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    return tbc_admission_tables_exist();
}

function tbc_admission_get_config()
{
    $defaults = tbc_admission_default_config();
    if (!tbc_admission_tables_exist()) {
        return $defaults;
    }

    $row = sql_fetch(" select * from " . tbc_admission_config_table() . " where ad_id = " . (int) TBC_ADMISSION_CONFIG_ID);
    if (!$row) {
        return $defaults;
    }

    $config = array();
    foreach ($defaults as $key => $default) {
        $config[$key] = (isset($row[$key]) && $row[$key] !== '') ? $row[$key] : $default;
    }

    return $config;
}

function tbc_admission_save_config($data)
{
    tbc_admission_ensure_tables();

    $defaults = tbc_admission_default_config();
    $fields = array();
    foreach ($defaults as $key => $default) {
        $fields[$key] = isset($data[$key]) ? $data[$key] : $default;
    }

    $sets = array();
    foreach ($fields as $key => $value) {
        $sets[] = "`{$key}` = '" . sql_real_escape_string($value) . "'";
    }
    $sets[] = "`updated_at` = '" . G5_TIME_YMDHIS . "'";

    $exists = sql_fetch(" select ad_id from " . tbc_admission_config_table() . " where ad_id = " . (int) TBC_ADMISSION_CONFIG_ID);
    if ($exists) {
        sql_query(" update " . tbc_admission_config_table() . " set " . implode(', ', $sets) . " where ad_id = " . (int) TBC_ADMISSION_CONFIG_ID);
        return;
    }

    $insert = array('ad_id' => (int) TBC_ADMISSION_CONFIG_ID);
    foreach ($fields as $key => $value) {
        $insert[$key] = sql_real_escape_string($value);
    }
    $insert['updated_at'] = G5_TIME_YMDHIS;

    $parts = array();
    foreach ($insert as $col => $val) {
        $parts[] = "`{$col}` = '{$val}'";
    }
    sql_query(" insert into " . tbc_admission_config_table() . " set " . implode(', ', $parts));
}

function tbc_admission_get_steps($public_only = true)
{
    if (!tbc_admission_tables_exist()) {
        return tbc_admission_default_steps();
    }

    $where = $public_only ? ' where as_use = 1 ' : '';
    $result = sql_query(" select * from " . tbc_admission_step_table() . " {$where} order by as_order asc, as_id asc ");
    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    return $rows ? $rows : tbc_admission_default_steps();
}

function tbc_admission_get_points($public_only = true)
{
    if (!tbc_admission_tables_exist()) {
        return tbc_admission_default_points();
    }

    $where = $public_only ? ' where ap_use = 1 ' : '';
    $result = sql_query(" select * from " . tbc_admission_point_table() . " {$where} order by ap_order asc, ap_id asc ");
    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    return $rows ? $rows : tbc_admission_default_points();
}

function tbc_admission_get_flows($public_only = true)
{
    if (!tbc_admission_tables_exist()) {
        return tbc_admission_default_flows();
    }

    $where = $public_only ? ' where af_use = 1 ' : '';
    $result = sql_query(" select * from " . tbc_admission_flow_table() . " {$where} order by af_order asc, af_id asc ");
    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    return $rows ? $rows : tbc_admission_default_flows();
}

function tbc_admission_replace_steps($items)
{
    tbc_admission_ensure_tables();
    sql_query(" delete from " . tbc_admission_step_table());

    $order = 0;
    foreach ($items as $item) {
        $order++;
        $num = isset($item['as_num']) ? $item['as_num'] : sprintf('%02d', $order);
        $use = !empty($item['as_use']) ? 1 : 0;
        sql_query("
            insert into " . tbc_admission_step_table() . " set
                as_num = '" . sql_real_escape_string($num) . "',
                as_text_before = '" . sql_real_escape_string($item['as_text_before']) . "',
                as_text_em = '" . sql_real_escape_string($item['as_text_em']) . "',
                as_text_after = '" . sql_real_escape_string($item['as_text_after']) . "',
                as_desc = '" . sql_real_escape_string($item['as_desc']) . "',
                as_order = " . (int) $order . ",
                as_use = " . (int) $use . ",
                updated_at = '" . G5_TIME_YMDHIS . "'
        ");
    }
}

function tbc_admission_replace_points($items)
{
    tbc_admission_ensure_tables();
    sql_query(" delete from " . tbc_admission_point_table());

    $order = 0;
    foreach ($items as $item) {
        $order++;
        $use = !empty($item['ap_use']) ? 1 : 0;
        sql_query("
            insert into " . tbc_admission_point_table() . " set
                ap_label = '" . sql_real_escape_string($item['ap_label']) . "',
                ap_text = '" . sql_real_escape_string($item['ap_text']) . "',
                ap_order = " . (int) $order . ",
                ap_use = " . (int) $use . ",
                updated_at = '" . G5_TIME_YMDHIS . "'
        ");
    }
}

function tbc_admission_replace_flows($items)
{
    tbc_admission_ensure_tables();
    sql_query(" delete from " . tbc_admission_flow_table());

    $order = 0;
    foreach ($items as $item) {
        $order++;
        $use = !empty($item['af_use']) ? 1 : 0;
        sql_query("
            insert into " . tbc_admission_flow_table() . " set
                af_icon = '" . sql_real_escape_string($item['af_icon']) . "',
                af_label = '" . sql_real_escape_string($item['af_label']) . "',
                af_order = " . (int) $order . ",
                af_use = " . (int) $use . ",
                updated_at = '" . G5_TIME_YMDHIS . "'
        ");
    }
}

function tbc_admission_allowed_image_ext($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif', 'svg');
    return in_array($ext, $allowed, true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : '';
}

function tbc_admission_store_icon($file, $old_filename = '')
{
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return array('ok' => false, 'message' => '업로드된 파일이 없습니다.');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return array('ok' => false, 'message' => '파일 업로드 중 오류가 발생했습니다.');
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return array('ok' => false, 'message' => '파일 크기는 2MB 이하여야 합니다.');
    }

    $ext = tbc_admission_allowed_image_ext($file['name']);
    if (!$ext) {
        return array('ok' => false, 'message' => 'jpg, png, webp, gif, svg 파일만 업로드할 수 있습니다.');
    }

    tbc_admission_ensure_icon_dir();

    if ($old_filename) {
        $old = tbc_admission_icon_path() . '/' . $old_filename;
        if (is_file($old)) {
            @unlink($old);
        }
    }

    $filename = 'flow_' . date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
    $dest = tbc_admission_icon_path() . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return array('ok' => false, 'message' => '파일 저장에 실패했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    return array('ok' => true, 'filename' => $filename);
}

function tbc_admission_seed_defaults()
{
    if (!tbc_admission_ensure_tables()) {
        return false;
    }

    tbc_admission_ensure_icon_dir();

    $config_row = sql_fetch(" select ad_id from " . tbc_admission_config_table() . " where ad_id = " . (int) TBC_ADMISSION_CONFIG_ID);
    if (!$config_row) {
        tbc_admission_save_config(tbc_admission_default_config());
    }

    $step_count = sql_fetch(" select count(*) as cnt from " . tbc_admission_step_table());
    if (!(int) $step_count['cnt']) {
        tbc_admission_replace_steps(tbc_admission_default_steps());
    }

    $point_count = sql_fetch(" select count(*) as cnt from " . tbc_admission_point_table());
    if (!(int) $point_count['cnt']) {
        tbc_admission_replace_points(tbc_admission_default_points());
    }

    $flow_count = sql_fetch(" select count(*) as cnt from " . tbc_admission_flow_table());
    if (!(int) $flow_count['cnt']) {
        tbc_admission_replace_flows(tbc_admission_default_flows());
    }

    return true;
}

function tbc_admission_esc($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function tbc_admission_render_step_text($row)
{
    $html = tbc_admission_esc($row['as_text_before']);
    if (trim($row['as_text_em']) !== '') {
        $html .= '<em>' . tbc_admission_esc($row['as_text_em']) . '</em>';
    }
    $html .= tbc_admission_esc($row['as_text_after']);
    return $html;
}
