<?php
$sub_menu = '950360';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.notice.lib.php');
tbc_notice_ensure_tables();

$nt_id = isset($_POST['nt_id']) ? (int) $_POST['nt_id'] : 0;

$data = array(
    'nt_subject' => isset($_POST['nt_subject']) ? $_POST['nt_subject'] : '',
    'nt_content' => isset($_POST['nt_content']) ? $_POST['nt_content'] : '',
    'nt_author' => isset($_POST['nt_author']) ? $_POST['nt_author'] : '',
    'nt_is_notice' => !empty($_POST['nt_is_notice']) ? 1 : 0,
    'nt_use' => !empty($_POST['nt_use']) ? 1 : 0,
);

$result = tbc_notice_save($data, $nt_id);
if (!$result['ok']) {
    alert($result['message']);
}

$nt_id = $result['nt_id'];

if (isset($_FILES['nt_file']) && is_uploaded_file($_FILES['nt_file']['tmp_name'])) {
    $file_result = tbc_notice_update_file($nt_id, $_FILES['nt_file']);
    if (!$file_result['ok']) {
        alert($file_result['message']);
    }
}

goto_url('./tbc_notices.php?msg=' . urlencode('공지사항이 저장되었습니다.'));
