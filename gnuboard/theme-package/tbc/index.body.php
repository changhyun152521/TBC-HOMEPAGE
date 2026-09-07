<?php
/**
 * 메인 페이지 본문
 * apps/web 에서 자동 생성 — 직접 수정 시 build 스크립트 재실행 시 덮어씌워집니다.
 * 레이아웃·CSS 변경은 apps/web 에서 하고 npm run gnuboard:build 실행하세요.
 */
if (!defined('_GNUBOARD_')) exit;

$academy_main = tbc_academy_main_summary();
$academy_branch = tbc_academy_branch_summary();
$academy_total_count = tbc_academy_total_public_count();
$consult_slides = tbc_academy_get_consult_slides();
?>
<main id="sh_container">
        <!-- sh_container_wrapper [s] -->
		<div id="sh_container_wrapper">

            <!-- main_banner [s] -->
            <div id="main_banner">
                <div id="main_banner_wrap" data-aos="fade-up">
                    <div class="cont_box">
                        <div class="left">
                            <?php include_once(G5_THEME_PATH . '/partials/main-banner-hero.php'); ?>
                        </div>
                        <div class="right">
                            <div class="top_box">
                                <?php include_once(G5_THEME_PATH . '/partials/main-banner-right.php'); ?>
                                <ul>
                                    <li>
                                        <a href="<?php echo tbc_page_url('academies'); ?>">
                                            <div class="icon">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon01.png" alt="분원">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon01_on.png" class="img_on" alt="분원">
                                            </div>
                                            <p>분원</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo tbc_page_url('teachers'); ?>">
                                            <div class="icon">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon02.png" alt="강사진">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon02_on.png" class="img_on" alt="강사진">
                                            </div>
                                            <p>강사진</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo tbc_page_url('schedule'); ?>">
                                            <div class="icon">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon03.png" alt="시간표">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon03_on.png" class="img_on" alt="시간표">
                                            </div>
                                            <p>시간표</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo tbc_board_url('consult'); ?>">
                                            <div class="icon">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon04.png" alt="입학안내">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon04_on.png" class="img_on" alt="입학안내">
                                            </div>
                                            <p>입학안내</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="bot_box">
                                <?php include_once(G5_THEME_PATH . '/partials/main-consult-slider.php'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="notice_box">
                        <div id="sh_index_latest_id" class="sh_index_latest">
                            <p class="tit"><i data-feather="volume-2"></i> 공지사항</p>
                            <div id="index_btm">  
                                <div class="swiper index_btm_slide">
                                    <?php include_once(G5_THEME_PATH . '/partials/main-notice-slider.php'); ?>
                                    <div class="index_btm_pager">
                                        <div class="prev"><i class="fa fa-regular fa-angle-up" aria-hidden="true"></i></div>
                                        <div class="next"><i class="fa fa-regular fa-angle-down" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                var lateroll = new Swiper("#index_btm .index_btm_slide", {
                                  direction : 'vertical',
                                  loop : true,
                                  speed:800,
                                  autoplay: {
                                    delay: 2500,
                                    disableOnInteraction: false,
                                  },
                                  navigation: {
                                    nextEl: ".index_btm_pager .next",
                                    prevEl: ".index_btm_pager .prev",
                                  },
                                });
                              </script>
                            <a href="<?php echo tbc_board_url('notice'); ?>" class="more">더보기 <i data-feather="plus"></i></a>
                        </div> 
                    </div>    
                </div>
                
                <script>
                var mainSwiper = new Swiper(".main_slide", {
                    effect: "fade",
                    spaceBetween: 0,
                    speed:1000,
                    loop : true,
                    autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.bnr-next',
                        prevEl: '.bnr-prev',
                    },
                    pagination: {
                        el: '.page_num',
                        type: 'fraction',
                    },
                });
                $('.swiper-pause').click(function(){
                    mainSwiper.autoplay.stop();
                });
                $('.swiper-play').click(function(){
                    mainSwiper.autoplay.start();
                });
                $(function() { 
                    $(".play").click(function() {
                        $(this).toggleClass("on");
                    });
                });
                <?php if (count($consult_slides) > 1) { ?>
                (function() {
                    var wrap = document.querySelector('#main_banner_wrap .tbc-consult-slider-wrap');
                    if (!wrap || typeof Swiper === 'undefined') {
                        return;
                    }
                    var contact = wrap.closest('.contact');
                    var sliderEl = wrap.querySelector('.tbc_consult_slide');
                    var nextEl = contact ? contact.querySelector('.tbc-consult-next') : null;
                    var prevEl = contact ? contact.querySelector('.tbc-consult-prev') : null;
                    if (!sliderEl || !nextEl || !prevEl) {
                        return;
                    }
                    var consultSwiper = new Swiper(sliderEl, {
                        effect: 'fade',
                        fadeEffect: { crossFade: true },
                        slidesPerView: 1,
                        loop: true,
                        speed: 500,
                        autoplay: {
                            delay: 2000,
                            disableOnInteraction: false,
                        },
                        navigation: {
                            nextEl: nextEl,
                            prevEl: prevEl,
                        },
                    });
                    if (consultSwiper.autoplay && typeof consultSwiper.autoplay.start === 'function') {
                        consultSwiper.autoplay.start();
                    }
                })();
                <?php } ?>
                </script>
                
            </div>
            <!-- main_banner [e] -->

            <!-- sh_section [s] -->
            <section id="sh_section">
                <!-- inc01 [s] -->
                <article id="atc01">
                    <div class="inner">
                        <div class="top_box" data-aos="fade-down">
                            <img src="<?php echo G5_THEME_URL; ?>/img/main/inc01/img01.png" alt="메인이미지">
                            <?php include_once(G5_THEME_PATH . '/partials/main-section01-ko.php'); ?>
                        </div>
                        <div class="cont_inner">
                            <div class="center_box">
                                <div class="top_cont">
                                    <div class="tit_box">
                                        <h3 class="left" data-aos="fade-right">전문 강사진</h3>
                                        <div class="right" data-aos="fade-left"><a href="<?php echo tbc_page_url('teachers'); ?>">강사진 전체 보기 <em><i data-feather="arrow-up-right"></i></em></a></div>
                                    </div>
                                </div>
                                <?php include_once(G5_THEME_PATH . '/partials/main-teachers-gallery.php'); ?>
                            </div>
                            <div class="bot_box">
                                <div class="left" data-aos="fade-right">
                                    <div class="tit">
                                        대전·세종 <span><?php echo (int) $academy_total_count; ?>개 분원</span>이<br>
                                        하나의 브랜드로 연결되어 있습니다
                                    </div>
                                    <div class="review_box">
                                        <ul class=" n_lt">
                                            <li class="first">
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                </a>
                                                <div class="lt_cont_f">
                                                    <a href="<?php echo tbc_board_url('consult'); ?>">
                                                        <div class="left">
                                                            <div class="icon"><img src="<?php echo G5_THEME_URL; ?>/img/main/inc01/chat.png" alt="아이콘"></div>
                                                            <span class="date">02.21</span>
                                                        </div>
                                                        <div class="right">
                                                            <p class="subj">실력 향상을 위한 완벽한 선택!</p>
                                                            <p class="subt">강사님들은 매우 친절하고, 개념을 쉽게 설명해주셔서 쉽게따라갈 수 있었습니다. 특히 매 수업마다 실생활에.....</p>
                                                        </div>
                                                    </a>
                                                    <a href="<?php echo tbc_board_url('consult'); ?>" class="more">더보기 <i data-feather="plus"></i></a>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                    <div class="lt_cont">
                                                        <p class="subj">언제나 친절하고 유익한 강의를 해주셔서 감사합니다 !</p>
                                                        <span class="date">2025.02.21</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                    <div class="lt_cont">
                                                        <p class="subj">언제나 친절하고 유익한 강의를 해주셔서 감사합니다 !</p>
                                                        <span class="date">2025.02.21</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                    <div class="lt_cont">
                                                        <p class="subj">언제나 친절하고 유익한 강의를 해주셔서 감사합니다 !</p>
                                                        <span class="date">2025.02.21</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                    <div class="lt_cont">
                                                        <p class="subj">언제나 친절하고 유익한 강의를 해주셔서 감사합니다 !</p>
                                                        <span class="date">2025.02.21</span>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="right box" data-aos="fade-left">
                                    <div>
                                        <p>본원 <?php echo (int) $academy_main['count']; ?>개 전문관</p>
                                        <?php echo $academy_main['names_html'] ? $academy_main['names_html'] : '등록된 본원이 없습니다.'; ?>
                                    </div>
                                    <div>
                                        <p>대전·세종 <?php echo (int) $academy_branch['count']; ?>개 분원</p>
                                        <?php echo $academy_branch['names_html'] ? $academy_branch['names_html'] : '등록된 분원이 없습니다.'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <!-- inc01 [e] -->
                <!-- inc02 [s] -->
                <article id="atc02">
                    <div class="inner">
                        <div class="left" data-aos="fade-right">
                            <img src="<?php echo G5_THEME_URL; ?>/img/main/inc02/img01.png" alt="캐릭터">
                            <?php include_once(G5_THEME_PATH . '/partials/main-section02-left.php'); ?>
                        </div>
                        <div class="right" data-aos="fade-left">
                            <?php include_once(G5_THEME_PATH . '/partials/main-section02-right.php'); ?>
                        </div>
                    </div>
                </article>
                <!-- inc02 [e] -->
                <!-- inc03 [s] -->
                <article id="atc03">
                    <div class="inner">
                        <div class="tit_box">
                            <h2 data-aos="fade-down">공지사항 및 소식</h2>
                        </div>
                        <div class="cont_box">
                            <div class="left" data-aos="fade-right">
                                <div class="top">
                                    <ul class="late_tabs">
                                        <li class="on" rel="tab1">공지사항</li>
                                        <li rel="tab2">교육정보</li>
                                    </ul>
                                    <a href="<?php echo tbc_board_url('notice'); ?>">더보기 <em><i data-feather="arrow-up-right"></i></em></a>
                                </div>
                                <div class="bot">
                                    <div class="right">
                                        <div id="tabs">
                                            <div class="late_box">
                                                <div id="tab1" class="late_cont">
                                                    <div class="late">
                                                        <?php include_once(G5_THEME_PATH . '/partials/main-section03-notices.php'); ?>
                                                    </div>
                                                </div>
                                                <div id="tab2" class="late_cont">
                                                    <div class="late">
                                                        <?php include_once(G5_THEME_PATH . '/partials/main-section03-edu.php'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="right" data-aos="fade-left">
                                <div class="top">
                                    <p>교육 정보 미리보기</p>
                                    <a href="<?php echo tbc_page_url('edu'); ?>">더보기 <em><i data-feather="arrow-up-right"></i></em></a>
                                </div>
                                <div class="bot">
                                    <?php include_once(G5_THEME_PATH . '/partials/main-edu-banner.php'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                
                <script>
                $(function() { 
                    $(".late_cont").hide(); $(".late_cont:first").show();
                    $(".late_tabs li").click(function() {$(".late_tabs li").removeClass("on");
                    $(this).addClass("on"); $(".late_cont").hide() 
                    var activeTab = $(this).attr("rel"); $("#"+activeTab).show()
                });                 });   
                $(document).mouseup(function (e){
                    var LayerPopup = $("#video_view .inner");
                    if(LayerPopup.has(e.target).length === 0){
                    $('#video_view').remove();
                    }
                });

                var eduBannerSwiper = new Swiper("#tbcEduBannerSlide", {
                    effect: "fade",
                    loop: true,
                    speed: 800,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                    },
                    on: {
                        init: function () {
                            updateTbcEduBannerText(this);
                        },
                        slideChange: function () {
                            updateTbcEduBannerText(this);
                        }
                    }
                });

                function updateTbcEduBannerText(swiper) {
                    var slide = swiper.slides[swiper.activeIndex];
                    if (!slide) return;

                    var link = document.getElementById('tbcEduBannerLink');
                    var subject = document.getElementById('tbcEduBannerSubject');
                    var meta = document.getElementById('tbcEduBannerMeta');
                    var tag = document.getElementById('tbcEduBannerTag');
                    if (!link || !subject || !meta) return;

                    link.href = slide.getAttribute('data-url') || '<?php echo tbc_page_url('edu'); ?>';
                    subject.textContent = slide.getAttribute('data-subject') || '';
                    meta.textContent = slide.getAttribute('data-meta') || '';
                    if (tag) {
                        tag.textContent = slide.getAttribute('data-tag') || '';
                    }
                }
                </script>
                <!-- inc03 [e] -->
            </section> 
            <!-- sh_section [e] -->
        </div>
        <!-- sh_container_wrapper [e] --> 
    </main>