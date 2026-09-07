<?php
if (!defined('_GNUBOARD_')) exit;

$tbc_latest_notices = function_exists('tbc_notice_latest') ? tbc_notice_latest(3) : array();
?>
<ul class="swiper-wrapper">
<?php if ($tbc_latest_notices) { ?>
    <?php foreach ($tbc_latest_notices as $tbc_notice_row) { ?>
    <li class="swiper-slide">
        <span class="sh_notice">
            <a href="<?php echo htmlspecialchars(tbc_notice_view_url($tbc_notice_row['nt_id']), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($tbc_notice_row['nt_subject'], ENT_QUOTES, 'UTF-8'); ?></a>
            <p class="date"><?php echo htmlspecialchars(date('Y.m.d', strtotime($tbc_notice_row['created_at'])), ENT_QUOTES, 'UTF-8'); ?></p>
        </span>
    </li>
    <?php } ?>
<?php } else { ?>
    <li class="swiper-slide">
        <span class="sh_notice">
            <a href="<?php echo tbc_page_url('notice'); ?>">등록된 공지사항이 없습니다.</a>
            <p class="date"><?php echo date('Y.m.d'); ?></p>
        </span>
    </li>
<?php } ?>
</ul>
