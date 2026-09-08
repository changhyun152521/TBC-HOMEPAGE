<?php
$setup_key = 'tbc-bands-2026';
if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');
include_once(G5_THEME_PATH . '/tbc.band.lib.php');

header('Content-Type: text/plain; charset=utf-8');

if (!tbc_band_ensure_tables()) {
    exit("ERROR: 테이블 생성 실패\n");
}

echo "tables OK\n";
tbc_band_seed_defaults();
echo "seed OK\n";
echo "band count: " . count(tbc_band_get_list()) . "\n";
echo "\nDONE\n";
