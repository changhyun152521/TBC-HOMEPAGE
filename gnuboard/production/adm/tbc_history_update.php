<?php
$sub_menu = '950210';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

$token = isset($_POST['token']) ? $_POST['token'] : '';
$session_token = get_session('ss_admin_token');
if (!$token || !$session_token || $token !== $session_token) {
    alert('토큰이 만료되었습니다.', './tbc_history.php');
}

include_once(G5_THEME_PATH . '/tbc.history.lib.php');
tbc_history_ensure_tables();

$result = tbc_history_save_config($_POST);
if (!$result['ok']) {
    alert(isset($result['message']) ? $result['message'] : '저장에 실패했습니다.', './tbc_history.php');
}

goto_url('./tbc_history.php?msg=' . urlencode('상단 문구가 저장되었습니다.'));
