<?php
$sub_menu = '950350';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.schedule.lib.php');
tbc_schedule_ensure_tables();

$sc_id = isset($_POST['sc_id']) ? (int) $_POST['sc_id'] : 0;
$messages = array();

$slots = array();
if (!empty($_POST['slots']) && is_array($_POST['slots'])) {
    foreach ($_POST['slots'] as $slot) {
        if (empty($slot['day'])) {
            continue;
        }
        $slots[] = array(
            'day' => $slot['day'],
            'start' => isset($slot['start']) ? $slot['start'] : '',
            'end' => isset($slot['end']) ? $slot['end'] : '',
        );
    }
}

$data = array(
    'sc_academy' => isset($_POST['sc_academy']) ? $_POST['sc_academy'] : '',
    'sc_grade' => isset($_POST['sc_grade']) ? $_POST['sc_grade'] : '',
    'sc_subject' => isset($_POST['sc_subject']) ? $_POST['sc_subject'] : '',
    'sc_name' => isset($_POST['sc_name']) ? $_POST['sc_name'] : '',
    'sc_teacher' => isset($_POST['sc_teacher']) ? $_POST['sc_teacher'] : '',
    'sc_fee' => isset($_POST['sc_fee']) ? $_POST['sc_fee'] : '',
    'sc_use' => !empty($_POST['sc_use']) ? 1 : 0,
    'slots' => $slots,
);

$result = tbc_schedule_save($data, $sc_id);
if (!$result['ok']) {
    alert($result['message']);
}

$sc_id = (int) $result['sc_id'];

if (isset($_FILES['schedule_image']) && is_uploaded_file($_FILES['schedule_image']['tmp_name'])) {
    $image_result = tbc_schedule_update_image($sc_id, $_FILES['schedule_image']);
    if (!$image_result['ok']) {
        $messages[] = $image_result['message'];
    }
}

$redirect = './tbc_schedules.php';
if (!$messages) {
    $redirect .= '?msg=' . urlencode($sc_id && isset($_POST['sc_id']) && (int) $_POST['sc_id'] ? '강좌가 수정되었습니다.' : '강좌가 등록되었습니다.');
} else {
    $redirect = './tbc_schedule_form.php?sc_id=' . $sc_id . '&msg=' . urlencode(implode("\n", $messages));
}

goto_url($redirect);
