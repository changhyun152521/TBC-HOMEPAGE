<?php
/**
 * TBC 분원 DB 초기화 (1회 실행)
 * /gnuboard5/install-tbc-academies.php?key=tbc-academies-2026
 */
$setup_key = 'tbc-academies-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');
include_once(G5_THEME_PATH . '/tbc.academy.lib.php');

header('Content-Type: text/plain; charset=utf-8');

if (!tbc_academy_ensure_tables()) {
    exit("ERROR: 테이블 생성 실패\n");
}

echo "tables OK\n";

if (!tbc_academy_seed_defaults()) {
    exit("ERROR: 기본 데이터 입력 실패\n");
}

$items = tbc_academy_get_list();
echo "seed OK\n";
echo "academy count: " . count($items) . "\n";
echo "\nDONE\n";
