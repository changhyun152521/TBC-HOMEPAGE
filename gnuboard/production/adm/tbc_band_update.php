<?php
$sub_menu = '950225';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.band.lib.php');
tbc_band_ensure_tables();

$bd_id = isset($_POST['bd_id']) ? (int) $_POST['bd_id'] : 0;
$data = array(
    'bd_title' => isset($_POST['bd_title']) ? $_POST['bd_title'] : '',
    'bd_url' => isset($_POST['bd_url']) ? $_POST['bd_url'] : '',
    'bd_use' => !empty($_POST['bd_use']) ? 1 : 0,
);

if (trim($data['bd_title']) === '' || trim($data['bd_url']) === '') {
    alert('제목과 URL은 필수입니다.');
}

$result = tbc_band_save($data, $bd_id);
if (!$result['ok']) {
    alert('저장에 실패했습니다.');
}

$msg = $bd_id ? 'BAND 링크가 수정되었습니다.' : 'BAND 링크가 등록되었습니다.';
goto_url('./tbc_bands.php?msg=' . urlencode($msg));
