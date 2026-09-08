<?php

$sub_menu = '950300';

require_once './_common.php';



header('Content-Type: application/json; charset=utf-8');



auth_check_menu($auth, $sub_menu, 'w');



$token = isset($_POST['token']) ? $_POST['token'] : '';

$session_token = get_session('ss_admin_token');

if (!$token || !$session_token || $token !== $session_token) {

    echo json_encode(array('ok' => false, 'message' => '토큰이 만료되었습니다. 페이지를 새로고침 후 다시 시도하세요.'));

    exit;

}



include_once(G5_THEME_PATH . '/tbc.teacher.lib.php');

tbc_teacher_ensure_tables();



$action = isset($_POST['action']) ? $_POST['action'] : '';



if ($action === 'move_teacher') {

    $tc_id = isset($_POST['tc_id']) ? (int) $_POST['tc_id'] : 0;

    $direction = isset($_POST['direction']) ? $_POST['direction'] : '';

    $scope = isset($_POST['scope']) ? preg_replace('/[^a-z]/', '', $_POST['scope']) : '';

    echo json_encode(tbc_teacher_move($tc_id, $direction, $scope));

    exit;

}



if ($action === 'delete_teacher') {

    $tc_id = isset($_POST['tc_id']) ? (int) $_POST['tc_id'] : 0;

    if ($tc_id < 1) {

        echo json_encode(array('ok' => false, 'message' => '잘못된 요청입니다.'));

        exit;

    }

    echo json_encode(tbc_teacher_delete($tc_id));

    exit;

}



if ($action === 'move_image') {

    $ti_id = isset($_POST['ti_id']) ? (int) $_POST['ti_id'] : 0;

    $direction = isset($_POST['direction']) ? $_POST['direction'] : '';

    echo json_encode(tbc_teacher_move_image($ti_id, $direction));

    exit;

}



if ($action === 'delete_image') {

    $ti_id = isset($_POST['ti_id']) ? (int) $_POST['ti_id'] : 0;

    if ($ti_id < 1) {

        echo json_encode(array('ok' => false, 'message' => '잘못된 요청입니다.'));

        exit;

    }

    echo json_encode(tbc_teacher_delete_image($ti_id));

    exit;

}



echo json_encode(array('ok' => false, 'message' => '지원하지 않는 요청입니다.'));

