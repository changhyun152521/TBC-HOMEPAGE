<?php
if (!defined('_GNUBOARD_')) exit;

$tbc_main_edu = function_exists('tbc_edu_latest') ? tbc_edu_latest(4) : array();
?>
<ul class=" n_lt">
<?php if ($tbc_main_edu) { ?>
    <?php foreach ($tbc_main_edu as $tbc_edu_row) { ?>
    <li>
        <a href="<?php echo htmlspecialchars(tbc_edu_view_url($tbc_edu_row['ed_id']), ENT_QUOTES, 'UTF-8'); ?>">
            <div class="lt_cont_f">
                <p class="cate"><?php echo htmlspecialchars(trim($tbc_edu_row['ed_meta1_dt']) !== '' ? $tbc_edu_row['ed_meta1_dt'] : '교육정보', ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="subj"><?php echo htmlspecialchars($tbc_edu_row['ed_subject'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="subt"><?php echo htmlspecialchars(tbc_edu_strip_text($tbc_edu_row['ed_content']), ENT_QUOTES, 'UTF-8'); ?></p>
                <span class="date"><?php echo htmlspecialchars(date('Y.m.d', strtotime($tbc_edu_row['created_at'])), ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </a>
    </li>
    <?php } ?>
<?php } else { ?>
    <li>
        <a href="<?php echo tbc_page_url('edu'); ?>">
            <div class="lt_cont_f">
                <p class="cate">교육정보</p>
                <p class="subj">등록된 교육정보가 없습니다.</p>
                <p class="subt">관리자 페이지에서 교육정보를 등록해 주세요.</p>
                <span class="date"><?php echo date('Y.m.d'); ?></span>
            </div>
        </a>
    </li>
<?php } ?>
</ul>
