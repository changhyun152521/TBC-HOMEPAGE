<?php
/**
 * TBC 입학절차 DB 초기화 (1회 실행)
 * /gnuboard5/install-tbc-admission.php?key=tbc-admission-2026
 */
$setup_key = 'tbc-admission-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');
include_once(G5_THEME_PATH . '/tbc.admission.lib.php');

header('Content-Type: text/plain; charset=utf-8');

if (!tbc_admission_ensure_tables()) {
    exit("ERROR: 테이블 생성 실패\n");
}

echo "tables OK\n";

if (!tbc_admission_seed_defaults()) {
    exit("ERROR: 기본 데이터 입력 실패\n");
}

$config = tbc_admission_get_config();
echo "seed OK\n";
echo "heading: " . $config['ad_heading'] . "\n";
echo "steps: " . count(tbc_admission_get_steps(false)) . "\n";
echo "points: " . count(tbc_admission_get_points(false)) . "\n";
echo "flows: " . count(tbc_admission_get_flows(false)) . "\n";
echo "\nDONE\n";
