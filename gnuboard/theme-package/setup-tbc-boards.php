<?php
/**
 * TBC 게시판 1회 생성 — notice, edu, review, consult
 * 실행: /gnuboard5/setup-tbc-boards.php?key=tbc-boards-2026
 * 완료 후 서버에서 이 파일을 삭제하세요.
 */
$setup_key = 'tbc-boards-2026';
if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');

header('Content-Type: text/plain; charset=utf-8');

$boards = array(
    array('notice', '공지사항'),
    array('edu', '교육정보'),
    array('review', '수강후기'),
    array('consult', '상담신청'),
);

function tbc_escape($str)
{
    if (function_exists('sql_real_escape_string')) {
        return sql_real_escape_string($str);
    }
    return addslashes($str);
}

function tbc_write_table_exists($bo_table)
{
    global $g5;
    $create_table = $g5['write_prefix'] . $bo_table;
    $row = sql_fetch(" show tables like '{$create_table}' ");
    return (bool) $row;
}

function tbc_create_write_table($bo_table)
{
    global $g5;

    if (tbc_write_table_exists($bo_table)) {
        return 'exists';
    }

    $sql_file = G5_ADMIN_PATH . '/sql_write.sql';
    if (!is_file($sql_file)) {
        return 'missing_sql_write';
    }

    $file = file($sql_file);
    if (function_exists('get_db_create_replace')) {
        $file = get_db_create_replace($file);
    }

    $sql = implode("\n", $file);
    $create_table = $g5['write_prefix'] . $bo_table;
    $sql = preg_replace(array('/__TABLE_NAME__/', '/;/'), array($create_table, ''), $sql);
    sql_query($sql, false);

    $board_path = G5_DATA_PATH . '/file/' . $bo_table;
    @mkdir($board_path, G5_DIR_PERMISSION);
    @chmod($board_path, G5_DIR_PERMISSION);

    $index = $board_path . '/index.php';
    if ($fp = @fopen($index, 'w')) {
        fwrite($fp, '');
        fclose($fp);
        @chmod($index, G5_FILE_PERMISSION);
    }

    return 'created';
}

$template = sql_fetch(" select * from {$g5['board_table']} where bo_table = 'free' ");
if (!$template) {
    $template = sql_fetch(" select * from {$g5['board_table']} order by bo_table limit 1 ");
}

if (!$template) {
    exit("ERROR: 기준 게시판이 없습니다. 관리자에서 게시판을 하나 먼저 만든 뒤 다시 실행하세요.\n");
}

echo "template={$template['bo_table']}\n";

foreach ($boards as $board) {
    list($bo_table, $bo_subject) = $board;

    $exists = sql_fetch(" select count(*) as cnt from {$g5['board_table']} where bo_table = '" . tbc_escape($bo_table) . "' ");
    if ($exists['cnt']) {
        echo "[SKIP] {$bo_table} — 이미 있음\n";
        continue;
    }

    $table_result = tbc_create_write_table($bo_table);
    echo "[TABLE] {$bo_table}: {$table_result}\n";

    $sets = array();
    foreach ($template as $key => $value) {
        if (in_array($key, array('bo_table', 'bo_subject', 'bo_mobile_subject', 'bo_count_write', 'bo_count_comment', 'bo_use_secret', 'bo_write_level'), true)) {
            continue;
        }
        $sets[] = "`{$key}` = '" . tbc_escape($value) . "'";
    }

    $sets[] = "bo_table = '" . tbc_escape($bo_table) . "'";
    $sets[] = "bo_subject = '" . tbc_escape($bo_subject) . "'";
    $sets[] = "bo_mobile_subject = '" . tbc_escape($bo_subject) . "'";
    $sets[] = "bo_count_write = '0'";
    $sets[] = "bo_count_comment = '0'";

    if ($bo_table === 'consult') {
        $sets[] = "bo_use_secret = '1'";
        $sets[] = "bo_write_level = '1'";
    }

    $sql = " insert into {$g5['board_table']} set " . implode(', ', $sets);
    sql_query($sql);

    echo "[OK] {$bo_table} ({$bo_subject})\n";
}

echo "\nDONE\n";
