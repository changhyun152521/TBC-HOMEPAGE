<?php
if (!defined('_GNUBOARD_')) exit;

$consult_slides = tbc_academy_get_consult_slides();
?>
<div class="contact<?php echo ($consult_slides && count($consult_slides) > 1) ? ' has-nav' : ''; ?>">
    <?php if ($consult_slides) { ?>
    <?php if (count($consult_slides) > 1) { ?>
    <button type="button" class="tbc-consult-prev" aria-label="이전 상담 정보"><span aria-hidden="true">‹</span></button>
    <button type="button" class="tbc-consult-next" aria-label="다음 상담 정보"><span aria-hidden="true">›</span></button>
    <?php } ?>
    <div class="tbc-consult-slider-wrap">
        <div class="swiper tbc_consult_slide">
            <div class="swiper-wrapper">
                <?php foreach ($consult_slides as $slide) { ?>
                <div class="swiper-slide">
                    <div class="top">
                        <div class="s_tit">
                            <div class="phone"><i data-feather="phone"></i></div>상담문의(<?php echo htmlspecialchars($slide['name'], ENT_QUOTES, 'UTF-8'); ?>)
                        </div>
                        <div class="tel">
                            <?php if ($slide['phone']) { ?>
                            <p><?php echo htmlspecialchars($slide['phone'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php } ?>
                            언제나 친절한 상담을 약속드립니다.
                        </div>
                    </div>
                    <?php if ($slide['hours']) { ?>
                    <div class="time_box"><?php echo htmlspecialchars($slide['hours'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } else { ?>
    <div class="top">
        <div class="s_tit"><div class="phone"><i data-feather="phone"></i></div>상담문의</div>
        <div class="tel">
            <p>042-000-0000</p>
            언제나 친절한 상담을 약속드립니다.
        </div>
    </div>
    <div class="time_box">평일 14:00~22:00 · 주말 및 공휴일 10:00~22:00</div>
    <?php } ?>
</div>
