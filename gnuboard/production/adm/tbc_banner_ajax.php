<?php
$sub_menu = '950100';
require_once './_common.php';

header('Content-Type: application/json; charset=utf-8');

auth_check_menu($auth, $sub_menu, 'w');

$token = isset($_POST['token']) ? $_POST['token'] : '';
$session_token = get_session('ss_admin_token');
if (!$token || !$session_token || $token !== $session_token) {
    echo json_encode(array('ok' => false, 'message' => '토큰이 만료되었습니다. 페이지를 새로고침 후 다시 시도하세요.'));
    exit;
}

include_once(G5_THEME_PATH . '/tbc.main.lib.php');
tbc_main_ensure_tables();

$action = isset($_POST['action']) ? $_POST['action'] : '';
$bn_id = isset($_POST['bn_id']) ? (int) $_POST['bn_id'] : 0;

if ($action === 'move') {
    $direction = isset($_POST['direction']) ? $_POST['direction'] : '';
    $result = tbc_main_move_banner($bn_id, $direction);
    echo json_encode($result);
    exit;
}

if ($action === 'delete') {
    if ($bn_id < 1) {
        echo json_encode(array('ok' => false, 'message' => '잘못된 요청입니다.'));
        exit;
    }
    $result = tbc_main_delete_banner_and_normalize($bn_id);
    echo json_encode($result);
    exit;
}

echo json_encode(array('ok' => false, 'message' => '지원하지 않는 요청입니다.'));
