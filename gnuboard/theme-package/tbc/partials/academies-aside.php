<?php
if (!defined('_GNUBOARD_')) exit;

if (!isset($tbc_academy_nav)) {
    $tbc_academy_nav = 'all';
}
?>
<div id="sh_aside">
    <div id="sh_aside_wrapper">
        <ul id="shSnb">
            <li<?php echo $tbc_academy_nav === 'all' ? ' class="on"' : ''; ?>>
                <a href="<?php echo tbc_page_url('academies'); ?>">전체</a>
            </li>
            <li<?php echo $tbc_academy_nav === 'main' ? ' class="on"' : ''; ?>>
                <a href="<?php echo tbc_page_url('academies_main'); ?>">본원</a>
            </li>
            <li<?php echo $tbc_academy_nav === 'branch' ? ' class="on"' : ''; ?>>
                <a href="<?php echo tbc_page_url('academies_branch'); ?>">분원</a>
            </li>
        </ul>
    </div>
</div>
