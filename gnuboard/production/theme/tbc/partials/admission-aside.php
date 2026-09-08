<?php
if (!defined('_GNUBOARD_')) exit;

if (!isset($tbc_admission_nav)) {
    $tbc_admission_nav = 'admission';
}
?>
<div id="sh_aside">
    <div id="sh_aside_wrapper">
        <ul id="shSnb">
            <li<?php echo $tbc_admission_nav === 'consult' ? ' class="on"' : ''; ?>>
                <a href="<?php echo tbc_page_url('consult'); ?>">상담신청</a>
            </li>
            <li<?php echo $tbc_admission_nav === 'admission' ? ' class="on"' : ''; ?>>
                <a href="<?php echo tbc_page_url('admission'); ?>">입학절차</a>
            </li>
            <li<?php echo $tbc_admission_nav === 'faq' ? ' class="on"' : ''; ?>>
                <a href="<?php echo tbc_page_url('faq'); ?>">FAQ</a>
            </li>
        </ul>
    </div>
</div>
