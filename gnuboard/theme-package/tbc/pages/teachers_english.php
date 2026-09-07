<?php
/**
 * teachers-english.html → teachers_english
 * apps/web 에서 자동 생성 — 직접 수정 시 build 스크립트 재실행 시 덮어씌워집니다.
 * 레이아웃·CSS 변경은 apps/web 에서 하고 npm run gnuboard:build 실행하세요.
 */
if (!defined('_GNUBOARD_')) exit;
?>
<main id="sh_container">
            
        <!-- sh_container_wrapper [s] -->
        <div id="sh_container_wrapper">

            <!-- shSubBnr [s] -->
            <div id="sub_main_banner">
                <div id="shSubBnr">
                    <div class="sub_nav">
                        <p class="crumb" data-aos="fade-up" data-aos-delay="300">
                            <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>강사진 <i data-feather="chevron-right"></i>영어</p>
                        <p class="tit" data-aos="fade-up" data-aos-delay="600">영어</p>
                    </div>
                    <div id="mainImg" data-aos="fade-in" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/sub/bg_10.jpg);"></div>
                </div>
            </div>
            <!-- shSubBnr [e] -->

            <!-- sh_aside [s] -->
            <div id="sh_aside">
				<div id="sh_aside_wrapper">
                    <ul id="shSnb">
                        <li>
                            <a href="<?php echo tbc_page_url('teachers'); ?>">전체</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('teachers_korean'); ?>">국어</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('teachers_math'); ?>">수학</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('teachers_science'); ?>">과학</a>
                        </li>
                        <li class="on">
                            <a href="<?php echo tbc_page_url('teachers_english'); ?>">영어</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('teachers_social'); ?>">사회</a>
                        </li>
                    </ul>
                </div>
			</div>
            <!-- sh_aside [e] -->
            
            <!-- sh_content [s] -->
            <div id="sh_content">
                <!-- 서브페이지 [s] -->
                <div id="members1005">
                    <div class="inner">
                        <div class="tit_wrap">
                            <h3 class="tit">실력과 진정성으로 검증된 강사진</h3>
                            <p class="txt">
                                더브레인코어의 강사진은 단순히 가르치는 사람이 아닌, 학생의 성장을 함께 설계하는 교육 파트너입니다.<br/>
                                풍부한 현장 경험과 체계적인 커리큘럼을 바탕으로 학생 개개인에 맞는 최적의 학습 방향을 제시합니다.
                            </p>
                        </div>
                        <?php $tbc_teacher_subject = 'english'; include_once(G5_THEME_PATH . '/partials/teachers-list.php'); ?>
                    </div>
                </div>
                <!-- 서브페이지 [e] -->
                
            </div>
            <!-- sh_content [e] -->            
            
        </div>
        <!-- sh_container_wrapper [e] -->
    </main>

