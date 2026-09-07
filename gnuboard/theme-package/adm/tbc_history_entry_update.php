<?php
$sub_menu = '950210';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'w');

$token = isset($_POST['token']) ? $_POST['token'] : '';
$session_token = get_session('ss_admin_token');
if (!$token || !$session_token || $token !== $session_token) {
    alert('토큰이 만료되었습니다.', './tbc_history.php');
}

include_once(G5_THEME_PATH . '/tbc.history.lib.php');
tbc_history_ensure_tables();

$he_id = isset($_POST['he_id']) ? (int) $_POST['he_id'] : 0;
$result = tbc_history_save_entry($_POST, $he_id);

if (!$result['ok']) {
    $redirect = './tbc_history_entry_form.php';
    if ($he_id) {
        $redirect .= '?he_id=' . $he_id;
    }
    alert(isset($result['message']) ? $result['message'] : '저장에 실패했습니다.', $redirect);
}

$msg = $he_id ? '연혁이 수정되었습니다.' : '연혁이 등록되었습니다.';
goto_url('./tbc_history.php?msg=' . urlencode($msg));
