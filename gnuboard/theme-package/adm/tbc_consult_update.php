<?php
$sub_menu = '950390';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.consult.lib.php');

$cf_id = isset($_POST['cf_id']) ? (int) $_POST['cf_id'] : 0;
$data = array(
    'ac_id' => isset($_POST['ac_id']) ? (int) $_POST['ac_id'] : 0,
    'cf_title' => isset($_POST['cf_title']) ? $_POST['cf_title'] : '',
    'cf_url' => isset($_POST['cf_url']) ? $_POST['cf_url'] : '',
    'cf_use' => !empty($_POST['cf_use']) ? 1 : 0,
);

$result = tbc_consult_save($data, $cf_id);
if (!$result['ok']) {
    alert($result['message']);
}

$msg = $cf_id ? '구글폼 링크가 수정되었습니다.' : '구글폼 링크가 등록되었습니다.';
goto_url('./tbc_consults.php?msg=' . urlencode($msg));
