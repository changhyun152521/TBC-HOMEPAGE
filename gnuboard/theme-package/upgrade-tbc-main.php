<?php
/**
 * TBC 메인 DB 스키마 업그레이드 (1회 실행)
 * /gnuboard5/upgrade-tbc-main.php?key=tbc-main-2026
 */
$setup_key = 'tbc-main-2026';
if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');
include_once(G5_THEME_PATH . '/tbc.main.lib.php');

header('Content-Type: text/plain; charset=utf-8');

tbc_main_ensure_tables();
tbc_main_seed_defaults();

$config = tbc_main_get_config();
$banners = tbc_main_get_all_banners();

echo "schema OK\n";
echo "right_tagline: " . $config['right_tagline'] . "\n";
echo "banner count: " . count($banners) . "\n";
echo "\nDONE\n";
