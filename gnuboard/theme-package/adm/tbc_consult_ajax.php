<?php
$sub_menu = '950390';
require_once './_common.php';

header('Content-Type: application/json; charset=utf-8');

if ($is_admin != 'super') {
    echo json_encode(array('ok' => false, 'message' => '최고관리자만 접근 가능합니다.'));
    exit;
}

auth_check_menu($auth, $sub_menu, 'w');

$token = isset($_POST['token']) ? $_POST['token'] : '';
$session_token = get_session('ss_admin_token');
if (!$token || !$session_token || $token !== $session_token) {
    echo json_encode(array('ok' => false, 'message' => '토큰이 만료되었습니다.'));
    exit;
}

include_once(G5_THEME_PATH . '/tbc.consult.lib.php');
tbc_consult_ensure_tables();

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'move_consult') {
    echo json_encode(tbc_consult_move((int) $_POST['cf_id'], isset($_POST['direction']) ? $_POST['direction'] : ''));
    exit;
}

if ($action === 'delete_consult') {
    echo json_encode(tbc_consult_delete((int) $_POST['cf_id']));
    exit;
}

echo json_encode(array('ok' => false, 'message' => '지원하지 않는 요청입니다.'));
