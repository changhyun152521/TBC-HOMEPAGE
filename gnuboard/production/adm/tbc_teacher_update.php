<?php

$sub_menu = '950300';

require_once './_common.php';



auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();



include_once(G5_THEME_PATH . '/tbc.teacher.lib.php');

tbc_teacher_ensure_tables();



$tc_id = isset($_POST['tc_id']) ? (int) $_POST['tc_id'] : 0;

$messages = array();



$data = array(

    'tc_subject' => isset($_POST['tc_subject']) ? preg_replace('/[^a-z]/', '', $_POST['tc_subject']) : '',

    'tc_name' => isset($_POST['tc_name']) ? $_POST['tc_name'] : '',

    'tc_slug' => isset($_POST['tc_slug']) ? $_POST['tc_slug'] : '',

    'tc_subject_label' => isset($_POST['tc_subject_label']) ? $_POST['tc_subject_label'] : '',

    'tc_tagline' => isset($_POST['tc_tagline']) ? $_POST['tc_tagline'] : '',

    'tc_bg_tags' => isset($_POST['tc_bg_tags']) ? $_POST['tc_bg_tags'] : '',

    'tc_round_tags' => isset($_POST['tc_round_tags']) ? $_POST['tc_round_tags'] : '',

    'tc_expertise' => isset($_POST['tc_expertise']) ? $_POST['tc_expertise'] : '',

    'tc_career' => isset($_POST['tc_career']) ? $_POST['tc_career'] : '',

    'tc_use' => !empty($_POST['tc_use']) ? 1 : 0,

);



if ($data['tc_subject'] === '' || trim($data['tc_name']) === '') {

    alert('과목과 강사 이름은 필수입니다.');

}



$result = tbc_teacher_save($data, $tc_id);

if (!$result['ok']) {

    alert('저장에 실패했습니다.');

}



$tc_id = (int) $result['tc_id'];



if (isset($_FILES['profile_image']) && is_uploaded_file($_FILES['profile_image']['tmp_name'])) {

    $image_result = tbc_teacher_update_profile_image($tc_id, $_FILES['profile_image']);

    if (!$image_result['ok']) {

        $messages[] = '프로필 사진: ' . $image_result['message'];

    }

}



if (isset($_FILES['curriculum_images'])) {

    $curriculum_result = tbc_teacher_add_images($tc_id, 'curriculum', $_FILES['curriculum_images']);

    if (!$curriculum_result['ok']) {

        $messages[] = '커리큘럼 사진: ' . $curriculum_result['message'];

    }

}



if (isset($_FILES['intro_images'])) {

    $intro_result = tbc_teacher_add_images($tc_id, 'intro', $_FILES['intro_images']);

    if (!$intro_result['ok']) {

        $messages[] = '소개 사진: ' . $intro_result['message'];

    }

}



$redirect = './tbc_teacher_form.php?tc_id=' . $tc_id;

if ($messages) {

    $redirect .= '&msg=' . urlencode(implode("\n", $messages));

} else {

    $redirect .= '&msg=' . urlencode('저장되었습니다.');

}



goto_url($redirect);

