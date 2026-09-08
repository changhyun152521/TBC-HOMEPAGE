<?php
/**
 * TBC 전용 부관리자 계정 생성 (1회 실행)
 * /gnuboard5/install-tbc-admin.php?key=tbc-admin-2026
 *
 * 아이디: tbc / 비밀번호: 1
 * TBC 관리(menu950) 메뉴만 접근 가능
 */
$setup_key = 'tbc-admin-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $setup_key) {
    http_response_code(403);
    exit('Forbidden');
}

include_once('./_common.php');

header('Content-Type: text/plain; charset=utf-8');

$mb_id = 'tbc';
$password = '1';
$mb_name = 'TBC관리';
$mb_nick = 'TBC관리';

$tbc_menus = array(
    '950000',
    '950100',
    '950120',
    '950200',
    '950210',
    '950225',
    '950250',
    '950300',
    '950350',
    '950360',
    '950370',
    '950380',
    '950390',
);

$member = get_member($mb_id);
$hash = get_encrypt_string($password);

if (!$member['mb_id']) {
    $sql = " insert into {$g5['member_table']} set
        mb_id = '" . sql_real_escape_string($mb_id) . "',
        mb_password = '" . sql_real_escape_string($hash) . "',
        mb_name = '" . sql_real_escape_string($mb_name) . "',
        mb_nick = '" . sql_real_escape_string($mb_nick) . "',
        mb_email = 'tbc@tbctest.local',
        mb_level = '2',
        mb_mailling = '0',
        mb_sms = '0',
        mb_open = '0',
        mb_nick_date = '" . G5_TIME_YMD . "',
        mb_email_certify = '" . G5_TIME_YMDHIS . "',
        mb_datetime = '" . G5_TIME_YMDHIS . "',
        mb_ip = '" . sql_real_escape_string($_SERVER['REMOTE_ADDR']) . "' ";
    sql_query($sql);
    echo "member created: {$mb_id}\n";
} else {
    sql_query(" update {$g5['member_table']} set
        mb_password = '" . sql_real_escape_string($hash) . "',
        mb_name = '" . sql_real_escape_string($mb_name) . "',
        mb_nick = '" . sql_real_escape_string($mb_nick) . "',
        mb_level = '2'
        where mb_id = '" . sql_real_escape_string($mb_id) . "' ");
    echo "member updated: {$mb_id}\n";
}

sql_query(" delete from {$g5['auth_table']} where mb_id = '" . sql_real_escape_string($mb_id) . "' ");

foreach ($tbc_menus as $au_menu) {
    sql_query(" insert into {$g5['auth_table']} set
        mb_id = '" . sql_real_escape_string($mb_id) . "',
        au_menu = '" . sql_real_escape_string($au_menu) . "',
        au_auth = 'r,w,d' ");
}

echo 'auth menus: ' . count($tbc_menus) . "\n";
echo "\nDONE\n";
echo "로그인: {$mb_id} / {$password}\n";
echo "관리자 URL: " . G5_ADMIN_URL . "\n";
