<?php
$sub_menu = '950370';
require_once './_common.php';

header('Content-Type: application/json; charset=utf-8');

if ($is_admin != 'super') {
    echo json_encode(array('ok' => false, 'message' => '권한이 없습니다.'));
    exit;
}

auth_check_menu($auth, $sub_menu, 'w');

$token = isset($_POST['token']) ? $_POST['token'] : '';
$session_token = get_session('ss_admin_token');
if (!$token || !$session_token || $token !== $session_token) {
    echo json_encode(array('ok' => false, 'message' => '토큰이 만료되었습니다. 페이지를 새로고침 후 다시 시도하세요.'));
    exit;
}

include_once(G5_THEME_PATH . '/tbc.edu.lib.php');
tbc_edu_ensure_tables();

$action = isset($_POST['action']) ? $_POST['action'] : '';
$ed_id = isset($_POST['ed_id']) ? (int) $_POST['ed_id'] : 0;

if ($action === 'delete') {
    echo json_encode(tbc_edu_delete($ed_id));
    exit;
}

if ($action === 'upload_image') {
    if (!isset($_FILES['image']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
        echo json_encode(array('ok' => false, 'message' => '이미지 파일이 없습니다.'));
        exit;
    }

    $stored = tbc_edu_store_file($_FILES['image']);
    echo json_encode($stored);
    exit;
}

echo json_encode(array('ok' => false, 'message' => '지원하지 않는 작업입니다.'));
