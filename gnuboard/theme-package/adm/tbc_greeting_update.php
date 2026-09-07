<?php

$sub_menu = '950200';

require_once './_common.php';



if ($is_admin != 'super') {

    alert('최고관리자만 접근 가능합니다.');

}



auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();



include_once(G5_THEME_PATH . '/tbc.greeting.lib.php');

tbc_greeting_ensure_table();



$fields = array();

foreach (array_keys(tbc_greeting_default_config()) as $key) {

    if ($key === 'gr_image') {

        continue;

    }

    $fields[$key] = isset($_POST[$key]) ? $_POST[$key] : '';

}



tbc_greeting_save_config($fields);



$messages = array();



if (isset($_FILES['greeting_image']) && is_uploaded_file($_FILES['greeting_image']['tmp_name'])) {

    $result = tbc_greeting_store_image($_FILES['greeting_image']);

    if (!$result['ok']) {

        $messages[] = '사진: ' . $result['message'];

    }

}



$redirect = './tbc_greeting.php';

if ($messages) {

    $redirect .= '?msg=' . urlencode(implode("\n", $messages));

}



goto_url($redirect);

