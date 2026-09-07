<?php
/**
 * sub1.html → greeting
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
                            <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>더브코 <i data-feather="chevron-right"></i>인사말</p>
                        <p class="tit" data-aos="fade-up" data-aos-delay="600">인사말</p>
                    </div>
                    <div id="mainImg" data-aos="fade-in" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/sub/bg_10.jpg);"></div>
                </div>
            </div>
            <!-- shSubBnr [e] -->

            <!-- sh_aside [s] -->
            <div id="sh_aside">
				<div id="sh_aside_wrapper">
                    <ul id="shSnb">
                        <li class="on">
                            <a href="<?php echo tbc_page_url('greeting'); ?>">인사말</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('philosophy'); ?>">교육철학</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('history'); ?>">연혁</a>
                        </li>
                    </ul>
                </div>
			</div>
            <!-- sh_aside [e] -->
            
            <!-- sh_content [s] -->
            <div id="sh_content">
                <!-- 서브페이지 [s] -->
                <div id="greeting" class="pagecommon">
                    <div class="tit_area">
                        하나의 교육 브랜드, 더브레인코어<br />
                        <p><b>더브레인코어</b>가 학생의 성장을 함께합니다.</p>
                    </div>
                    <div class="img"></div>
                    <div class="cont">
                        <p class="st">먼저 학부모님과 학생 여러분의 변함없는 관심과 신뢰에 깊이 감사드립니다.</p>
                        <div class="pl">더브레인코어는 대전·세종 지역에서 초등관, 중등관, 고등관, 과학관 등 본원 6개 전문관과
                        5개 지역 분원을 하나의 교육 브랜드로 연결하여 운영하고 있습니다.</div><br />  
                        <div class="pl">우리는 단기적인 성적 향상보다 학생의 전인적 성장과 학습 습관 형성을 중시하며,
                        체계적인 교육과정과 전문 강사진, 관리 시스템을 통해 신뢰할 수 있는 교육을 제공하고자 합니다.
                        앞으로도 더브레인코어는 학부모님과 학생 여러분의 든든한 교육 파트너가 되겠습니다.<br />              
                        감사합니다.</div>
                        <p class="sign">더브레인코어 <span>홍민호 대표</span></p>
                    </div>
                </div>
                <!-- 서브페이지 [e] -->
                
            </div>
            <!-- sh_content [e] -->            
            
        </div>
        <!-- sh_container_wrapper [e] -->
    </main>