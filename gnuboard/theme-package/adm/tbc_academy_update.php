<?php
$sub_menu = '950250';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.academy.lib.php');
tbc_academy_ensure_tables();

$ac_id = isset($_POST['ac_id']) ? (int) $_POST['ac_id'] : 0;
$messages = array();

$data = array(
    'ac_type' => isset($_POST['ac_type']) ? preg_replace('/[^a-z]/', '', $_POST['ac_type']) : 'branch',
    'ac_name' => isset($_POST['ac_name']) ? $_POST['ac_name'] : '',
    'ac_region' => isset($_POST['ac_region']) ? $_POST['ac_region'] : '',
    'ac_address' => isset($_POST['ac_address']) ? $_POST['ac_address'] : '',
    'ac_phone' => isset($_POST['ac_phone']) ? $_POST['ac_phone'] : '',
    'ac_consult_phone' => isset($_POST['ac_consult_phone']) ? $_POST['ac_consult_phone'] : '',
    'ac_consult_hours' => isset($_POST['ac_consult_hours']) ? $_POST['ac_consult_hours'] : '',
    'ac_concept' => isset($_POST['ac_concept']) ? $_POST['ac_concept'] : '',
    'ac_map_url' => isset($_POST['ac_map_url']) ? $_POST['ac_map_url'] : '',
    'ac_use' => !empty($_POST['ac_use']) ? 1 : 0,
);

if (trim($data['ac_name']) === '') {
    alert('관 이름은 필수입니다.');
}

$result = tbc_academy_save($data, $ac_id);
if (!$result['ok']) {
    alert('저장에 실패했습니다.');
}

$ac_id = (int) $result['ac_id'];

if (isset($_FILES['academy_image']) && is_uploaded_file($_FILES['academy_image']['tmp_name'])) {
    $image_result = tbc_academy_update_image($ac_id, $_FILES['academy_image']);
    if (!$image_result['ok']) {
        $messages[] = $image_result['message'];
    }
}

$redirect = './tbc_academies.php';
if (!$messages) {
    $redirect .= '?msg=' . urlencode($ac_id && isset($_POST['ac_id']) && (int) $_POST['ac_id'] ? '분원 정보가 수정되었습니다.' : '분원이 등록되었습니다.');
} else {
    $redirect = './tbc_academy_form.php?ac_id=' . $ac_id . '&msg=' . urlencode(implode("\n", $messages));
}

goto_url($redirect);
