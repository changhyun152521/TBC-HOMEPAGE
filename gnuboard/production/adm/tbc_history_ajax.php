<?php
$sub_menu = '950210';
require_once './_common.php';

header('Content-Type: application/json; charset=utf-8');

auth_check_menu($auth, $sub_menu, 'w');

$token = isset($_POST['token']) ? $_POST['token'] : '';
$session_token = get_session('ss_admin_token');
if (!$token || !$session_token || $token !== $session_token) {
    echo json_encode(array('ok' => false, 'message' => '토큰이 만료되었습니다.'));
    exit;
}

include_once(G5_THEME_PATH . '/tbc.history.lib.php');
tbc_history_ensure_tables();

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'move_entry') {
    echo json_encode(tbc_history_move((int) $_POST['he_id'], isset($_POST['direction']) ? $_POST['direction'] : ''));
    exit;
}

if ($action === 'delete_entry') {
    echo json_encode(tbc_history_delete((int) $_POST['he_id']));
    exit;
}

echo json_encode(array('ok' => false, 'message' => '지원하지 않는 요청입니다.'));
