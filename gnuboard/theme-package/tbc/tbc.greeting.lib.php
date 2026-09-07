<?php
/**
 * TBC 더브코 인사말 관리
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_media_url')) {
    include_once(G5_THEME_PATH . '/tbc.lib.php');
}

define('TBC_GREETING_CONFIG_ID', 1);

function tbc_greeting_config_table()
{
    return G5_TABLE_PREFIX . 'tbc_greeting_config';
}

function tbc_greeting_image_path()
{
    return G5_DATA_PATH . '/tbc/greeting';
}

function tbc_greeting_image_url($filename)
{
    return tbc_media_url('greeting', $filename);
}

function tbc_greeting_ensure_image_dir()
{
    $dir = tbc_greeting_image_path();
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

function tbc_greeting_table_exists()
{
    $row = sql_fetch(" show tables like '" . tbc_greeting_config_table() . "' ");
    return (bool) $row;
}

function tbc_greeting_column_exists($column)
{
    $table = tbc_greeting_config_table();
    $row = sql_fetch(" show columns from `{$table}` like '{$column}' ");
    return (bool) $row;
}

function tbc_greeting_default_config()
{
    return array(
        'heading' => '하나의 교육 브랜드, 더브레인코어',
        'subheading_bold' => '더브레인코어',
        'subheading' => '가 학생의 성장을 함께합니다.',
        'body' => "먼저 학부모님과 학생 여러분의 변함없는 관심과 신뢰에 깊이 감사드립니다.\n\n더브레인코어는 대전·세종 지역에서 초등관, 중등관, 고등관, 과학관 등 본원 6개 전문관과\n5개 지역 분원을 하나의 교육 브랜드로 연결하여 운영하고 있습니다.\n\n우리는 단기적인 성적 향상보다 학생의 전인적 성장과 학습 습관 형성을 중시하며,\n체계적인 교육과정과 전문 강사진, 관리 시스템을 통해 신뢰할 수 있는 교육을 제공하고자 합니다.\n앞으로도 더브레인코어는 학부모님과 학생 여러분의 든든한 교육 파트너가 되겠습니다.\n감사합니다.",
        'sign_org' => '더브레인코어',
        'sign_name' => '홍민호 대표',
        'gr_image' => '',
    );
}

function tbc_greeting_admin_field_groups()
{
    return array(
        array(
            'step' => '①',
            'title' => '상단 — 제목 영역',
            'desc' => '인사말 페이지 맨 위에 보이는 큰 제목입니다.',
            'fields' => array(
                array('key' => 'heading', 'label' => '큰 제목 (첫 줄)', 'rows' => 2),
                array('key' => 'subheading_bold', 'label' => '부제목 — 굵게 표시할 단어', 'rows' => 1, 'single_line' => true),
                array('key' => 'subheading', 'label' => '부제목 — 나머지 문장', 'rows' => 1, 'single_line' => true),
            ),
        ),
        array(
            'step' => '②',
            'title' => '중간 — 대표 사진',
            'desc' => '제목 아래에 보이는 넓은 사진입니다. 아래에서 교체할 수 있습니다.',
            'fields' => array(),
        ),
        array(
            'step' => '③',
            'title' => '하단 — 인사말 본문',
            'desc' => '사진 아래에 나오는 인사말 글입니다.',
            'fields' => array(
                array('key' => 'body', 'label' => '본문', 'rows' => 10),
            ),
        ),
        array(
            'step' => '④',
            'title' => '맨 아래 — 서명',
            'desc' => '인사말 글 마지막에 나오는 이름·직함입니다.',
            'fields' => array(
                array('key' => 'sign_org', 'label' => '브랜드명 (왼쪽)', 'rows' => 1, 'single_line' => true),
                array('key' => 'sign_name', 'label' => '이름·직함 (오른쪽)', 'rows' => 1, 'single_line' => true),
            ),
        ),
    );
}

function tbc_greeting_upgrade_schema()
{
    if (!tbc_greeting_table_exists()) {
        return;
    }

    $table = tbc_greeting_config_table();
    $defaults = tbc_greeting_default_config();

    foreach ($defaults as $column => $default_value) {
        if (!tbc_greeting_column_exists($column)) {
            $type = ($column === 'gr_image') ? 'varchar(255) not null default \'\'' : 'text not null';
            sql_query(" alter table `{$table}` add `{$column}` {$type} ", false);
        }
    }

    tbc_greeting_migrate_to_single_body();
    tbc_greeting_fill_empty_columns();
}

function tbc_greeting_migrate_to_single_body()
{
    if (!tbc_greeting_table_exists()) {
        return;
    }

    $table = tbc_greeting_config_table();

    if (!tbc_greeting_column_exists('body')) {
        sql_query(" alter table `{$table}` add `body` text not null ", false);
    }

    $row = sql_fetch(" select * from `{$table}` where id = " . TBC_GREETING_CONFIG_ID);
    if (!$row) {
        return;
    }

    $body = isset($row['body']) ? trim($row['body']) : '';
    if ($body !== '') {
        return;
    }

    $parts = array();
    foreach (array('lead', 'body1', 'body2') as $column) {
        if (tbc_greeting_column_exists($column) && !empty($row[$column])) {
            $parts[] = trim($row[$column]);
        }
    }

    if (!$parts) {
        return;
    }

    $merged = sql_real_escape_string(implode("\n\n", $parts));
    sql_query(" update `{$table}` set `body` = '{$merged}' where id = " . TBC_GREETING_CONFIG_ID);
}

function tbc_greeting_fill_empty_columns()
{
    $defaults = tbc_greeting_default_config();
    $table = tbc_greeting_config_table();
    $row = sql_fetch(" select * from `{$table}` where id = " . TBC_GREETING_CONFIG_ID);
    if (!$row) {
        return;
    }

    foreach ($defaults as $key => $default) {
        if ($key === 'gr_image') {
            continue;
        }
        if (!array_key_exists($key, $row) || $row[$key] === '') {
            $value = sql_real_escape_string($default);
            sql_query(" update `{$table}` set `{$key}` = '{$value}' where id = " . TBC_GREETING_CONFIG_ID);
        }
    }
}

function tbc_greeting_ensure_table()
{
    if (tbc_greeting_table_exists()) {
        tbc_greeting_upgrade_schema();
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_greeting_config_table() . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `heading` text NOT NULL,
            `subheading_bold` text NOT NULL,
            `subheading` text NOT NULL,
            `body` text NOT NULL,
            `sign_org` text NOT NULL,
            `sign_name` text NOT NULL,
            `gr_image` varchar(255) NOT NULL DEFAULT '',
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    tbc_greeting_upgrade_schema();
    return tbc_greeting_table_exists();
}

function tbc_greeting_get_config()
{
    $defaults = tbc_greeting_default_config();

    if (!tbc_greeting_table_exists()) {
        return $defaults;
    }

    tbc_greeting_upgrade_schema();

    $columns = implode(', ', array_keys($defaults));
    $row = sql_fetch(" select {$columns} from " . tbc_greeting_config_table() . " where id = " . TBC_GREETING_CONFIG_ID);

    if (!$row) {
        return $defaults;
    }

    $config = array();
    foreach ($defaults as $key => $default) {
        $config[$key] = (isset($row[$key]) && $row[$key] !== '') ? $row[$key] : $default;
    }

    return $config;
}

function tbc_greeting_resolve_image_url($filename)
{
    return tbc_media_resolve_url('greeting', $filename, G5_THEME_URL . '/img/sub/greeting_img.jpg');
}

function tbc_greeting_save_config($fields)
{
    tbc_greeting_ensure_table();

    $defaults = tbc_greeting_default_config();
    $sets = array();
    $insert_sets = array('id' => TBC_GREETING_CONFIG_ID);

    foreach ($defaults as $key => $default) {
        if ($key === 'gr_image') {
            continue;
        }
        $value = isset($fields[$key]) ? $fields[$key] : $default;
        $escaped = sql_real_escape_string($value);
        $sets[] = "`{$key}` = '{$escaped}'";
        $insert_sets[$key] = $escaped;
    }

    $sets[] = "`updated_at` = '" . G5_TIME_YMDHIS . "'";
    $insert_sets['updated_at'] = G5_TIME_YMDHIS;

    $exists = sql_fetch(" select id from " . tbc_greeting_config_table() . " where id = " . TBC_GREETING_CONFIG_ID);
    if ($exists) {
        sql_query(" update " . tbc_greeting_config_table() . " set " . implode(', ', $sets) . " where id = " . TBC_GREETING_CONFIG_ID);
        return;
    }

    $insert_parts = array();
    foreach ($insert_sets as $col => $val) {
        $insert_parts[] = "`{$col}` = '{$val}'";
    }
    sql_query(" insert into " . tbc_greeting_config_table() . " set " . implode(', ', $insert_parts));
}

function tbc_greeting_validate_upload_file($file)
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

    $ext = tbc_greeting_allowed_image_ext($file['name']);
    if (!$ext) {
        return array('ok' => false, 'message' => 'jpg, png, webp, gif 파일만 업로드할 수 있습니다.');
    }

    return array('ok' => true, 'ext' => $ext);
}

function tbc_greeting_allowed_image_ext($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    return in_array($ext, $allowed, true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : '';
}

function tbc_greeting_store_image($file)
{
    $check = tbc_greeting_validate_upload_file($file);
    if (!$check['ok']) {
        return $check;
    }

    tbc_greeting_ensure_image_dir();

    $filename = 'greeting_' . date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $check['ext'];
    $dest = tbc_greeting_image_path() . '/' . $filename;

    $config = tbc_greeting_get_config();
    if (!empty($config['gr_image'])) {
        $old = tbc_greeting_image_path() . '/' . $config['gr_image'];
        if (is_file($old)) {
            @unlink($old);
        }
    }

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return array('ok' => false, 'message' => '파일 저장에 실패했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    $image = sql_real_escape_string($filename);
    tbc_greeting_ensure_table();
    $exists = sql_fetch(" select id from " . tbc_greeting_config_table() . " where id = " . TBC_GREETING_CONFIG_ID);
    if ($exists) {
        sql_query("
            update " . tbc_greeting_config_table() . "
            set gr_image = '{$image}', updated_at = '" . G5_TIME_YMDHIS . "'
            where id = " . TBC_GREETING_CONFIG_ID . "
        ");
    } else {
        tbc_greeting_save_config(tbc_greeting_default_config());
        sql_query("
            update " . tbc_greeting_config_table() . "
            set gr_image = '{$image}', updated_at = '" . G5_TIME_YMDHIS . "'
            where id = " . TBC_GREETING_CONFIG_ID . "
        ");
    }

    return array('ok' => true, 'filename' => $filename);
}

function tbc_greeting_seed_defaults()
{
    if (!tbc_greeting_ensure_table()) {
        return false;
    }

    tbc_greeting_ensure_image_dir();

    $defaults = tbc_greeting_default_config();
    $row = sql_fetch(" select id, gr_image from " . tbc_greeting_config_table() . " where id = " . TBC_GREETING_CONFIG_ID);

    if (!$row) {
        tbc_greeting_save_config($defaults);
        $row = sql_fetch(" select gr_image from " . tbc_greeting_config_table() . " where id = " . TBC_GREETING_CONFIG_ID);
    } else {
        tbc_greeting_fill_empty_columns();
    }

    if (empty($row['gr_image'])) {
        $source = G5_THEME_PATH . '/img/sub/greeting_img.jpg';
        $dest_name = 'greeting_main.jpg';
        $dest = tbc_greeting_image_path() . '/' . $dest_name;

        if (is_file($source) && !is_file($dest)) {
            if (@copy($source, $dest)) {
                @chmod($dest, G5_FILE_PERMISSION);
                $image = sql_real_escape_string($dest_name);
                sql_query("
                    update " . tbc_greeting_config_table() . "
                    set gr_image = '{$image}'
                    where id = " . TBC_GREETING_CONFIG_ID . "
                ");
            }
        }
    }

    return true;
}

function tbc_greeting_format_html($text)
{
    return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}

function tbc_greeting_format_text($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function tbc_greeting_render_body($text)
{
    $text = trim(preg_replace("/\r\n?/", "\n", (string) $text));
    if ($text === '') {
        return '';
    }

    $paragraphs = preg_split('/\n\s*\n/', $text);
    $paragraphs = array_values(array_filter(array_map('trim', $paragraphs), 'strlen'));

    if (!$paragraphs) {
        return '';
    }

    if (count($paragraphs) === 1) {
        return '<div class="pl">' . tbc_greeting_format_text($paragraphs[0]) . '</div>';
    }

    $html = '<p class="st">' . tbc_greeting_format_text($paragraphs[0]) . '</p>';

    $rest = array_slice($paragraphs, 1);
    foreach ($rest as $index => $paragraph) {
        if ($index > 0) {
            $html .= '<br />';
        }
        $html .= '<div class="pl">' . tbc_greeting_format_text($paragraph) . '</div>';
    }

    return $html;
}
