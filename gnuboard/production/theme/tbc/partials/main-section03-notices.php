<?php
if (!defined('_GNUBOARD_')) exit;

$tbc_main_notices = function_exists('tbc_notice_latest') ? tbc_notice_latest(4) : array();
?>
<ul class=" n_lt">
<?php if ($tbc_main_notices) { ?>
    <?php foreach ($tbc_main_notices as $tbc_notice_row) { ?>
    <li>
        <a href="<?php echo htmlspecialchars(tbc_notice_view_url($tbc_notice_row['nt_id']), ENT_QUOTES, 'UTF-8'); ?>">
            <div class="lt_cont_f">
                <p class="cate">NOTICE</p>
                <p class="subj"><?php echo htmlspecialchars($tbc_notice_row['nt_subject'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="subt"><?php echo htmlspecialchars(tbc_notice_strip_text($tbc_notice_row['nt_content']), ENT_QUOTES, 'UTF-8'); ?></p>
                <span class="date"><?php echo htmlspecialchars(date('Y.m.d', strtotime($tbc_notice_row['created_at'])), ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </a>
    </li>
    <?php } ?>
<?php } else { ?>
    <li>
        <a href="<?php echo tbc_page_url('notice'); ?>">
            <div class="lt_cont_f">
                <p class="cate">NOTICE</p>
                <p class="subj">등록된 공지사항이 없습니다.</p>
                <p class="subt">관리자 페이지에서 공지사항을 등록해 주세요.</p>
                <span class="date"><?php echo date('Y.m.d'); ?></span>
            </div>
        </a>
    </li>
<?php } ?>
</ul>
