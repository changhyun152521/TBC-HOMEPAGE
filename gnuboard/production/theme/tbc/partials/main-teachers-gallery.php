<?php
if (!defined('_GNUBOARD_')) exit;

$main_teachers = tbc_teacher_get_main_featured(4);
?>
<div class="gall_box">
    <ul>
    <?php if ($main_teachers) {
        foreach ($main_teachers as $teacher) {
            $teacher_page = tbc_teacher_subject_page($teacher['tc_subject']);
            $teacher_href = tbc_page_url($teacher_page) . '#' . rawurlencode($teacher['tc_slug']);
            $teacher_name = tbc_teacher_short_name($teacher['tc_name']);
            $teacher_subject = $teacher['tc_subject_label'] ? $teacher['tc_subject_label'] : tbc_teacher_subject_label($teacher['tc_subject']);
            $teacher_img = htmlspecialchars($teacher['profile_url'], ENT_QUOTES, 'UTF-8');
    ?>
        <li>
            <a href="<?php echo htmlspecialchars($teacher_href, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="img" style="background-image:url('<?php echo $teacher_img; ?>')"></div>
                <div class="cont"><?php echo htmlspecialchars($teacher_name, ENT_QUOTES, 'UTF-8'); ?><span><?php echo htmlspecialchars($teacher_subject, ENT_QUOTES, 'UTF-8'); ?></span></div>
            </a>
        </li>
    <?php }
    } ?>
    </ul>
</div>
