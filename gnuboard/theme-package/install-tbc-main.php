<?php
/**
 * TBC 메인 배너·문구 DB 초기화 (1회 실행)
 * /gnuboard5/install-tbc-main.php?key=tbc-main-2026
 */
$setup_key = 'tbc-main-2026';
if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');
include_once(G5_THEME_PATH . '/tbc.main.lib.php');

header('Content-Type: text/plain; charset=utf-8');

if (!tbc_main_ensure_tables()) {
    exit("ERROR: 테이블 생성 실패\n");
}

echo "tables OK\n";

if (!tbc_main_seed_defaults()) {
    exit("ERROR: 기본 데이터 입력 실패\n");
}

echo "seed OK\n";
echo "config title: " . tbc_main_get_config()['main_title'] . "\n";
echo "banner count: " . count(tbc_main_get_all_banners()) . "\n";
echo "\nDONE\n";
