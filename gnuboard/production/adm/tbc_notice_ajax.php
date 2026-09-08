<?php
$sub_menu = '950360';
require_once './_common.php';

header('Content-Type: application/json; charset=utf-8');

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.notice.lib.php');
tbc_notice_ensure_tables();

$action = isset($_POST['action']) ? $_POST['action'] : '';
$nt_id = isset($_POST['nt_id']) ? (int) $_POST['nt_id'] : 0;

if ($action === 'delete') {
    echo json_encode(tbc_notice_delete($nt_id));
    exit;
}

echo json_encode(array('ok' => false, 'message' => '지원하지 않는 작업입니다.'));
