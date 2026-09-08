<?php

/**

 * TBC 더브코 인사말 DB 초기화 (1회 실행)

 * /gnuboard5/install-tbc-greeting.php?key=tbc-greeting-2026

 */

$setup_key = 'tbc-greeting-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {

    http_response_code(403);

    exit('Forbidden');

}



include_once('./_common.php');

include_once(G5_THEME_PATH . '/tbc.greeting.lib.php');



header('Content-Type: text/plain; charset=utf-8');



if (!tbc_greeting_ensure_table()) {

    exit("ERROR: 테이블 생성 실패\n");

}



echo "tables OK\n";



if (!tbc_greeting_seed_defaults()) {

    exit("ERROR: 기본 데이터 입력 실패\n");

}



$config = tbc_greeting_get_config();

echo "seed OK\n";

echo "heading: " . $config['heading'] . "\n";

echo "image: " . ($config['gr_image'] ? $config['gr_image'] : '(theme fallback)') . "\n";

echo "\nDONE\n";

