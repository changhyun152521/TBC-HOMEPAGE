<?php
/**
 * schedule.html → schedule
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
                            <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>시간표 <i data-feather="chevron-right"></i>전체</p>
                        <p class="tit" data-aos="fade-up" data-aos-delay="600">전체 시간표</p>
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
                            <a href="<?php echo tbc_page_url('schedule'); ?>">전체</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('schedule_main'); ?>">본원</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('schedule_branch'); ?>">분원</a>
                        </li>
                    </ul>
                </div>
			</div>
            <!-- sh_aside [e] -->
            
            <!-- sh_content [s] -->
            <div id="sh_content">
                <!-- 서브페이지 [s] -->
                <div id="schedule1001" data-scope="all" data-academy="high">
                    <div class="sch_head">
                        <h3 class="sch_tit">전체 시간표</h3>
                        <p class="sch_desc">관·분원과 학년을 선택하면 해당 시간표를 확인할 수 있습니다.</p>
                    </div>

                    <div class="sch_section">
                        <strong class="sch_label">관·분원 선택</strong>
                        <div class="sch_group" id="schGroupMain">
                            <span class="sch_group_tit">본원</span>
                            <div class="sch_academy_list" id="schAcademyMain"></div>
                        </div>
                        <div class="sch_group" id="schGroupBranch">
                            <span class="sch_group_tit">분원</span>
                            <div class="sch_academy_list" id="schAcademyBranch"></div>
                        </div>
                    </div>

                    <div class="sch_section">
                        <strong class="sch_label">학년 선택</strong>
                        <div class="sch_grade_list" id="schGradeList"></div>
                    </div>

                    <div class="sch_result">
                        <h4 class="sch_result_tit" id="schResultTit">시간표</h4>
                        <ul class="sch_img_list" id="schImgList"></ul>
                        <div class="sch_empty" id="schEmpty" style="display:none;"></div>
                    </div>
                </div>
                <!-- 서브페이지 [e] -->
                
            </div>
            <!-- sh_content [e] -->            
            
        </div>
        <!-- sh_container_wrapper [e] -->
    </main>