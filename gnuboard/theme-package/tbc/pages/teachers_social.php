<?php
/**
 * teachers-social.html → teachers_social
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
                            <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>강사진 <i data-feather="chevron-right"></i>사회</p>
                        <p class="tit" data-aos="fade-up" data-aos-delay="600">사회</p>
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
                        <li>
                            <a href="<?php echo tbc_page_url('teachers_english'); ?>">영어</a>
                        </li>
                        <li class="on">
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
                        <ul class="instructor_list">
                            <li class="list01" data-aos="fade-up" style="justify-content:center;height:auto;padding:80px 40px;">
                                <div class="left_txt" style="text-align:center;width:100%;">
                                    <div class="l_name_wrap" style="margin-bottom:0;">
                                        <span class="subject">해당 과목 강사진은 준비 중입니다.</span>
                                        <b class="name">곧 업데이트됩니다</b>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- 서브페이지 [e] -->
                
            </div>
            <!-- sh_content [e] -->            
            
        </div>
        <!-- sh_container_wrapper [e] -->
    </main>