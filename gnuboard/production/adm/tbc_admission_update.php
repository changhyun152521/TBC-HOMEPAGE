<?php
$sub_menu = '950380';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

include_once(G5_THEME_PATH . '/tbc.admission.lib.php');
tbc_admission_ensure_tables();

$config_fields = array(
    'ad_heading' => isset($_POST['ad_heading']) ? $_POST['ad_heading'] : '',
    'ad_test_title' => isset($_POST['ad_test_title']) ? $_POST['ad_test_title'] : '',
    'ad_test_highlight' => isset($_POST['ad_test_highlight']) ? $_POST['ad_test_highlight'] : '',
    'ad_test_body' => isset($_POST['ad_test_body']) ? $_POST['ad_test_body'] : '',
);
tbc_admission_save_config($config_fields);

$steps = array();
$step_nums = isset($_POST['step_num']) ? $_POST['step_num'] : array();
$step_before = isset($_POST['step_before']) ? $_POST['step_before'] : array();
$step_em = isset($_POST['step_em']) ? $_POST['step_em'] : array();
$step_after = isset($_POST['step_after']) ? $_POST['step_after'] : array();
$step_desc = isset($_POST['step_desc']) ? $_POST['step_desc'] : array();
$step_use = isset($_POST['step_use']) ? $_POST['step_use'] : array();

for ($i = 0; $i < count($step_nums); $i++) {
    $steps[] = array(
        'as_num' => isset($step_nums[$i]) ? $step_nums[$i] : sprintf('%02d', $i + 1),
        'as_text_before' => isset($step_before[$i]) ? $step_before[$i] : '',
        'as_text_em' => isset($step_em[$i]) ? $step_em[$i] : '',
        'as_text_after' => isset($step_after[$i]) ? $step_after[$i] : '',
        'as_desc' => isset($step_desc[$i]) ? $step_desc[$i] : '',
        'as_use' => isset($step_use[$i]) ? 1 : 0,
    );
}
tbc_admission_replace_steps($steps);

$points = array();
$point_labels = isset($_POST['point_label']) ? $_POST['point_label'] : array();
$point_texts = isset($_POST['point_text']) ? $_POST['point_text'] : array();
$point_use = isset($_POST['point_use']) ? $_POST['point_use'] : array();

for ($i = 0; $i < count($point_labels); $i++) {
    $points[] = array(
        'ap_label' => isset($point_labels[$i]) ? $point_labels[$i] : '',
        'ap_text' => isset($point_texts[$i]) ? $point_texts[$i] : '',
        'ap_use' => isset($point_use[$i]) ? 1 : 0,
    );
}
tbc_admission_replace_points($points);

$flows = array();
$flow_labels = isset($_POST['flow_label']) ? $_POST['flow_label'] : array();
$flow_icons_old = isset($_POST['flow_icon_old']) ? $_POST['flow_icon_old'] : array();
$flow_use = isset($_POST['flow_use']) ? $_POST['flow_use'] : array();
$messages = array();

for ($i = 0; $i < count($flow_labels); $i++) {
    $icon = isset($flow_icons_old[$i]) ? $flow_icons_old[$i] : '';

    if (isset($_FILES['flow_icon']['tmp_name'][$i]) && is_uploaded_file($_FILES['flow_icon']['tmp_name'][$i])) {
        $file = array(
            'name' => $_FILES['flow_icon']['name'][$i],
            'type' => $_FILES['flow_icon']['type'][$i],
            'tmp_name' => $_FILES['flow_icon']['tmp_name'][$i],
            'error' => $_FILES['flow_icon']['error'][$i],
            'size' => $_FILES['flow_icon']['size'][$i],
        );
        $result = tbc_admission_store_icon($file, $icon);
        if ($result['ok']) {
            $icon = $result['filename'];
        } else {
            $messages[] = '아이콘 ' . ($i + 1) . ': ' . $result['message'];
        }
    }

    $flows[] = array(
        'af_icon' => $icon,
        'af_label' => isset($flow_labels[$i]) ? $flow_labels[$i] : '',
        'af_use' => isset($flow_use[$i]) ? 1 : 0,
    );
}
tbc_admission_replace_flows($flows);

$redirect = './tbc_admission.php';
if ($messages) {
    $redirect .= '?msg=' . urlencode(implode("\n", $messages));
}

goto_url($redirect);
