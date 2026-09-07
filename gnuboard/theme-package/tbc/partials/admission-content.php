<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_admission_get_config')) {
    include_once(G5_THEME_PATH . '/tbc.admission.lib.php');
}

tbc_admission_ensure_tables();
tbc_admission_seed_defaults();

$config = tbc_admission_get_config();
$steps = tbc_admission_get_steps(true);
$points = tbc_admission_get_points(true);
$flows = tbc_admission_get_flows(true);
?>
<div id="service1003" class="pagecommon">
    <p class="b_txt"><?php echo tbc_admission_esc($config['ad_heading']); ?></p>
    <ul class="num_list">
        <?php foreach ($steps as $step) { ?>
        <li>
            <p class="num"><span><?php echo tbc_admission_esc($step['as_num']); ?></span></p>
            <p class="txt">
                <?php echo tbc_admission_render_step_text($step); ?>
                <?php if (trim($step['as_desc']) !== '') { ?>
                <span><?php echo tbc_admission_esc($step['as_desc']); ?></span>
                <?php } ?>
            </p>
        </li>
        <?php } ?>
    </ul>
    <ul class="tip_list">
        <?php foreach ($points as $point) { ?>
        <li>
            <p class="tip_num"><?php echo tbc_admission_esc($point['ap_label']); ?></p>
            <p class="tip_txt"><?php echo tbc_admission_esc($point['ap_text']); ?></p>
        </li>
        <?php } ?>
    </ul>
    <div class="test_box">
        <div class="txt_box">
            <p class="tit"><?php echo tbc_admission_esc($config['ad_test_title']); ?></p>
            <p class="txt"><span><?php echo tbc_admission_esc($config['ad_test_highlight']); ?></span><?php echo tbc_admission_esc($config['ad_test_body']); ?></p>
        </div>
        <ul class="test_list">
            <?php foreach ($flows as $idx => $flow) { ?>
            <li>
                <img src="<?php echo htmlspecialchars(tbc_admission_resolve_icon_url($flow['af_icon'], $idx), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo tbc_admission_esc($flow['af_label']); ?>">
                <?php echo tbc_admission_esc($flow['af_label']); ?>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
