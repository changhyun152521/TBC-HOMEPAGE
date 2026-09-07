<?php
if (!defined('_GNUBOARD_')) exit;

if (!isset($tbc_teacher_subject)) {
    $tbc_teacher_subject = '';
}

$teachers = tbc_teacher_get_public_list($tbc_teacher_subject);
?>
<ul class="instructor_list">
<?php if (!$teachers) { ?>
    <li class="list01" data-aos="fade-up" style="justify-content:center;height:auto;padding:80px 40px;">
        <div class="left_txt" style="text-align:center;width:100%;">
            <div class="l_name_wrap" style="margin-bottom:0;">
                <span class="subject">해당 과목 강사진은 준비 중입니다.</span>
                <b class="name">곧 업데이트됩니다</b>
            </div>
        </div>
    </li>
<?php } else {
    foreach ($teachers as $teacher) {
        $curriculum_attr = tbc_teacher_modal_images_attr($teacher['curriculum_images']);
        $intro_attr = tbc_teacher_modal_images_attr($teacher['intro_images']);
?>
    <li class="<?php echo htmlspecialchars($teacher['layout_class'], ENT_QUOTES, 'UTF-8'); ?>" id="<?php echo htmlspecialchars($teacher['tc_slug'], ENT_QUOTES, 'UTF-8'); ?>" data-aos="fade-up">
        <div class="img_box">
            <ul class="bg_txt">
                <?php foreach ($teacher['bg_tags'] as $tag) { ?>
                <li><?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php } ?>
            </ul>
            <img src="<?php echo $teacher['profile_url']; ?>" alt="<?php echo htmlspecialchars($teacher['tc_name'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="left_txt">
            <div class="l_top">
                <ul class="l_round_tit">
                    <?php foreach ($teacher['round_tags'] as $tag) { ?>
                    <li><?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php } ?>
                </ul>
                <div class="l_name_wrap">
                    <span class="subject"><?php echo htmlspecialchars($teacher['tc_subject_label'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <b class="name"><?php echo htmlspecialchars($teacher['tc_name'], ENT_QUOTES, 'UTF-8'); ?></b>
                </div>
            </div>
            <ul class="l_btn_wrap">
                <li><a href="#" class="js-teacher-modal" data-modal-images="<?php echo $curriculum_attr; ?>" data-modal-title="커리큘럼" data-modal-alt="강사 커리큘럼">커리큘럼 바로가기<i data-feather="chevron-right" class="icon"></i></a></li>
                <li><a href="#" class="js-teacher-modal" data-modal-images="<?php echo $intro_attr; ?>" data-modal-title="수업 특징" data-modal-alt="수업 특징">수업 특징 바로가기<i data-feather="chevron-right" class="icon"></i></a></li>
            </ul>
            <p class="l_txt"><?php echo htmlspecialchars($teacher['tc_tagline'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="right_txt">
            <ul class="r_history">
                <li>
                    <span class="h_t_tit">전문 지도 분야</span>
                    <ul class="h_b_txt">
                        <?php foreach ($teacher['expertise_items'] as $item) { ?>
                        <li><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php } ?>
                    </ul>
                </li>
                <li>
                    <span class="h_t_tit">학력 및 경력</span>
                    <ul class="h_b_txt">
                        <?php foreach ($teacher['career_items'] as $item) { ?>
                        <li><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </li>
<?php }
} ?>
</ul>
