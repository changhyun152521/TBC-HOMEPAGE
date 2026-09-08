<?php

/**

 * TBC 강사진 DB 초기화 (1회 실행)

 * /gnuboard5/install-tbc-teachers.php?key=tbc-teachers-2026

 */

$setup_key = 'tbc-teachers-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {

    http_response_code(403);

    exit('Forbidden');

}



include_once('./_common.php');

include_once(G5_THEME_PATH . '/tbc.teacher.lib.php');



header('Content-Type: text/plain; charset=utf-8');



if (!tbc_teacher_ensure_tables()) {

    exit("ERROR: 테이블 생성 실패\n");

}



echo "tables OK\n";



if (!tbc_teacher_seed_defaults()) {

    exit("ERROR: 기본 데이터 입력 실패\n");

}



$teachers = tbc_teacher_get_admin_list();

echo "seed OK\n";

echo "teacher count: " . count($teachers) . "\n";

echo "\nDONE\n";

