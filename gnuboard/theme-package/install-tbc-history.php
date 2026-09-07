<?php
/**
 * TBC 연혁 DB 초기화 (1회 실행)
 * /gnuboard5/install-tbc-history.php?key=tbc-history-2026
 */
$setup_key = 'tbc-history-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');
include_once(G5_THEME_PATH . '/tbc.history.lib.php');

header('Content-Type: text/plain; charset=utf-8');

if (!tbc_history_ensure_tables()) {
    exit("ERROR: 테이블 생성 실패\n");
}

if (!tbc_history_seed_defaults()) {
    exit("ERROR: 기본 데이터 생성 실패\n");
}

tbc_history_normalize_orders();

echo "OK: tbc_history_config, tbc_history_entry 테이블 준비 완료\n";
