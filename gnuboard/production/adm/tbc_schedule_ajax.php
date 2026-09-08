<?php
$sub_menu = '950350';
require_once './_common.php';

header('Content-Type: application/json; charset=utf-8');

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.schedule.lib.php');
tbc_schedule_ensure_tables();

$action = isset($_POST['action']) ? $_POST['action'] : '';
$sc_id = isset($_POST['sc_id']) ? (int) $_POST['sc_id'] : 0;

if ($action === 'delete') {
    $result = tbc_schedule_delete($sc_id);
    echo json_encode($result);
    exit;
}

if ($action === 'move') {
    $dir = isset($_POST['dir']) ? $_POST['dir'] : '';
    if ($dir !== 'up' && $dir !== 'down') {
        echo json_encode(array('ok' => false, 'message' => '잘못된 요청입니다.'));
        exit;
    }
    $result = tbc_schedule_move_order($sc_id, $dir);
    echo json_encode($result);
    exit;
}

echo json_encode(array('ok' => false, 'message' => '지원하지 않는 작업입니다.'));
