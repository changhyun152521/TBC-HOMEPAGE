<?php
$sub_menu = '950370';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.edu.lib.php');
tbc_edu_ensure_tables();

$ed_id = isset($_POST['ed_id']) ? (int) $_POST['ed_id'] : 0;

$data = array(
    'ed_subject' => isset($_POST['ed_subject']) ? $_POST['ed_subject'] : '',
    'ed_content' => isset($_POST['ed_content']) ? $_POST['ed_content'] : '',
    'ed_author' => isset($_POST['ed_author']) ? $_POST['ed_author'] : '',
    'ed_use' => !empty($_POST['ed_use']) ? 1 : 0,
);

$result = tbc_edu_save($data, $ed_id);
if (!$result['ok']) {
    alert($result['message']);
}

$ed_id = $result['ed_id'];

if (!empty($_POST['ed_thumb_remove'])) {
    tbc_edu_update_thumb($ed_id, array(), true);
}

if (isset($_FILES['ed_thumb_file']) && is_uploaded_file($_FILES['ed_thumb_file']['tmp_name'])) {
    $thumb_result = tbc_edu_update_thumb($ed_id, $_FILES['ed_thumb_file']);
    if (!$thumb_result['ok']) {
        alert($thumb_result['message']);
    }
}

goto_url('./tbc_edu.php?msg=' . urlencode('교육정보가 저장되었습니다.'));
