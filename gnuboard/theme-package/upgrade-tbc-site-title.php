<?php
/**
 * 사이트 제목 변경 (1회 실행)
 * /gnuboard5/upgrade-tbc-site-title.php?key=tbc-site-title-2026
 */
$setup_key = 'tbc-site-title-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');

header('Content-Type: text/plain; charset=utf-8');

$site_title = 'TBC 홈페이지 테스트용';

sql_query(" update {$g5['config_table']} set cf_title = '" . sql_real_escape_string($site_title) . "' ");

$config = sql_fetch(" select cf_title from {$g5['config_table']} ");

echo "cf_title: " . $config['cf_title'] . "\n";
echo "\nDONE\n";
