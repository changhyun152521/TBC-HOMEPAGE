<?php
$sub_menu = '950100';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.main.lib.php');
tbc_main_ensure_tables();

$fields = array();
foreach (array_keys(tbc_main_default_config()) as $key) {
    $fields[$key] = isset($_POST[$key]) ? $_POST[$key] : '';
}

tbc_main_save_config($fields);

$messages = array();

if (isset($_POST['banner_file']) && is_array($_POST['banner_file'])) {
    foreach ($_POST['banner_file'] as $bn_id => $file) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            continue;
        }
        $result = tbc_main_update_banner_image($bn_id, $file);
        if (!$result['ok']) {
            $messages[] = '배너 #' . (int) $bn_id . ': ' . $result['message'];
        }
    }
}

if (isset($_FILES['new_banner']) && is_uploaded_file($_FILES['new_banner']['tmp_name'])) {
    $result = tbc_main_add_banner($_FILES['new_banner']);
    if (!$result['ok']) {
        $messages[] = '새 배너: ' . $result['message'];
    }
}

$redirect = './tbc_main.php';
if ($messages) {
    $redirect .= '?msg=' . urlencode(implode("\n", $messages));
}

goto_url($redirect);
