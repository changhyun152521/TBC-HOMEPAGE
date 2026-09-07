<?php
/**
 * TBC 상담신청(구글폼) DB 초기화 (1회 실행)
 * /gnuboard5/install-tbc-consult.php?key=tbc-consult-2026
 */
$setup_key = 'tbc-consult-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');
include_once(G5_THEME_PATH . '/tbc.consult.lib.php');

header('Content-Type: text/plain; charset=utf-8');

if (!tbc_consult_ensure_tables()) {
    exit("ERROR: 테이블 생성 실패\n");
}

echo "tables OK\n";

if (!tbc_consult_seed_defaults()) {
    exit("ERROR: 기본 데이터 입력 실패\n");
}

echo "forms: " . count(tbc_consult_get_list()) . "\n";
echo "\nDONE\n";
