<?php

$sub_menu = '950120';

require_once './_common.php';



auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();



include_once(G5_THEME_PATH . '/tbc.menu.lib.php');

tbc_menu_ensure_tables();

tbc_menu_seed_defaults();



$messages = array();



if (isset($_FILES['panel_file']) && is_array($_FILES['panel_file']['name'])) {

    foreach ($_FILES['panel_file']['name'] as $key => $name) {

        if (!$name) {

            continue;

        }



        $file = array(

            'name' => $_FILES['panel_file']['name'][$key],

            'type' => $_FILES['panel_file']['type'][$key],

            'tmp_name' => $_FILES['panel_file']['tmp_name'][$key],

            'error' => $_FILES['panel_file']['error'][$key],

            'size' => $_FILES['panel_file']['size'][$key],

        );



        $result = tbc_menu_update_image($key, 'panel', $file);

        if (!$result['ok']) {

            alert($result['message']);

        }

        $messages[] = tbc_menu_definitions()[$key]['label'] . ' 패널 사진 저장';

    }

}



if (isset($_FILES['sub_banner_file']) && is_array($_FILES['sub_banner_file']['name'])) {

    foreach ($_FILES['sub_banner_file']['name'] as $key => $name) {

        if (!$name) {

            continue;

        }



        $file = array(

            'name' => $_FILES['sub_banner_file']['name'][$key],

            'type' => $_FILES['sub_banner_file']['type'][$key],

            'tmp_name' => $_FILES['sub_banner_file']['tmp_name'][$key],

            'error' => $_FILES['sub_banner_file']['error'][$key],

            'size' => $_FILES['sub_banner_file']['size'][$key],

        );



        $result = tbc_menu_update_image($key, 'sub_banner', $file);

        if (!$result['ok']) {

            alert($result['message']);

        }

        $messages[] = tbc_menu_definitions()[$key]['label'] . ' 상단 배너 저장';

    }

}



$msg = $messages ? implode("\n", $messages) : '변경된 파일이 없습니다.';

goto_url('./tbc_menu.php?msg=' . urlencode($msg));


