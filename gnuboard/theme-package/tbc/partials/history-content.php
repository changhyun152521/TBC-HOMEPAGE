<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_history_get_config')) {
    include_once(G5_THEME_PATH . '/tbc.history.lib.php');
}

tbc_history_ensure_tables();
tbc_history_seed_defaults();

$config = tbc_history_get_config();
$entries = tbc_history_get_public_entries();
?>
<div id="history1008" class="pagecommon inner">
    <div class="tit" data-aos="fade-right">
        <span><?php echo htmlspecialchars($config['hc_label_en'], ENT_QUOTES, 'UTF-8'); ?></span>
        <p class="pl"><b><?php echo htmlspecialchars($config['hc_title_bold'], ENT_QUOTES, 'UTF-8'); ?></b><?php echo htmlspecialchars($config['hc_title_text'], ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="pl"><?php echo htmlspecialchars($config['hc_desc'], ENT_QUOTES, 'UTF-8'); ?></div>
    </div>

    <div class="cont" data-aos="fade-left">
        <?php if (!$entries) { ?>
        <div class="hist_empty">등록된 연혁이 없습니다.</div>
        <?php } else { ?>
        <?php foreach ($entries as $entry) { ?>
        <div class="hist_block">
            <p class="hist_year"><?php echo htmlspecialchars($entry['he_year'], ENT_QUOTES, 'UTF-8'); ?></p>
            <ul>
                <?php foreach ($entry['items'] as $item) { ?>
                <li><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
        <?php } ?>
    </div>
</div>
