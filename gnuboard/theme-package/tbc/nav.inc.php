<?php
if (!defined('_GNUBOARD_')) exit;
?>
<!-- sh_wrapper [s] -->
<div id="sh_wrapper" class="<?php echo tbc_body_class(); ?>">

    <!-- sh_hd [s] -->
    <header id="sh_hd">
        <div id="sh_hd_wrapper">

            <div id="topmenu_wrapper">
                <div id="topBnr">
                    더브레인코어 입학 상담 접수 중 <a href="<?php echo tbc_board_url('consult'); ?>">상담신청</a>
                </div>
                <div id="shGnb" data-aos="fade-in">
                    <div id="top_logo">
                        <a href="<?php echo G5_URL; ?>"><img src="<?php echo G5_THEME_URL; ?>/img/common/logo.png" alt="더브레인코어"></a>
                    </div>
                    <nav class="sh_nav">
                        <ul id="top_nav">
                            <li class="list01">
                                <a href="<?php echo tbc_page_url('greeting'); ?>">더브코</a>
                                <ul>
                                    <li><a href="<?php echo tbc_page_url('greeting'); ?>">인사말</a></li>
                                    <li><a href="<?php echo tbc_page_url('philosophy'); ?>">교육철학</a></li>
                                    <li><a href="<?php echo tbc_page_url('history'); ?>">연혁</a></li>
                                </ul>
                            </li>
                            <li class="list02">
                                <a href="<?php echo tbc_page_url('academies'); ?>">관·분원</a>
                                <ul>
                                    <li><a href="<?php echo tbc_page_url('academies'); ?>">전체 관·분원</a></li>
                                    <li><a href="<?php echo tbc_page_url('academies_main'); ?>">본원 · 대전 둔산</a></li>
                                    <li><a href="<?php echo tbc_page_url('academies_branch'); ?>">분원 안내</a></li>
                                </ul>
                            </li>
                            <li class="list03">
                                <a href="<?php echo tbc_page_url('teachers'); ?>">강사진</a>
                                <ul>
                                    <li><a href="<?php echo tbc_page_url('teachers'); ?>">전체 강사진</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_korean'); ?>">국어</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_math'); ?>">수학</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_science'); ?>">과학</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_english'); ?>">영어</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_social'); ?>">사회</a></li>
                                </ul>
                            </li>
                            <li class="list04">
                                <a href="<?php echo tbc_page_url('schedule'); ?>">시간표</a>
                                <ul>
                                    <li><a href="<?php echo tbc_page_url('schedule'); ?>">전체 시간표</a></li>
                                    <li><a href="<?php echo tbc_page_url('schedule_main'); ?>">본원 시간표</a></li>
                                    <li><a href="<?php echo tbc_page_url('schedule_branch'); ?>">분원 시간표</a></li>
                                </ul>
                            </li>
                            <li class="list05">
                                <a href="<?php echo tbc_board_url('notice'); ?>">더브코 소식</a>
                                <ul>
                                    <li><a href="<?php echo tbc_board_url('notice'); ?>">공지사항</a></li>
                                    <li><a href="<?php echo tbc_board_url('edu'); ?>">교육정보</a></li>
                                    <li><a href="<?php echo tbc_board_url('review'); ?>">수강후기</a></li>
                                </ul>
                            </li>
                            <li class="list06">
                                <a href="<?php echo tbc_board_url('consult'); ?>">입학안내</a>
                                <ul>
                                    <li><a href="<?php echo tbc_board_url('consult'); ?>">상담신청</a></li>
                                    <li><a href="<?php echo tbc_page_url('admission'); ?>">입학절차</a></li>
                                    <li><a href="<?php echo tbc_page_url('faq'); ?>">FAQ</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    <a href="<?php echo tbc_board_url('consult'); ?>" class="insta">상담신청</a>
                </div>
                <div class="allmenu">
                    <div id="pfBtn" class=""><span></span></div>
                    <div id="allWrap" class="">
                        <div class="inner">
                            <div class="ci">
                                <a href="<?php echo G5_URL; ?>"><img src="<?php echo G5_THEME_URL; ?>/img/common/logo.png" alt="더브레인코어"></a>
                                <p><span>하나의 교육 브랜드</span> 더브레인코어</p>
                            </div>
                            <div class="cont sitemap">
                                <ul class="mn_img">
                                    <li><a class="bmn" href="<?php echo tbc_page_url('greeting'); ?>">더브코</a></li>
                                    <li><a class="bmn" href="<?php echo tbc_page_url('academies'); ?>">관·분원</a></li>
                                    <li><a class="bmn" href="<?php echo tbc_page_url('teachers'); ?>">강사진</a></li>
                                    <li><a class="bmn" href="<?php echo tbc_page_url('schedule'); ?>">시간표</a></li>
                                    <li><a class="bmn" href="<?php echo tbc_board_url('notice'); ?>">더브코 소식</a></li>
                                    <li><a class="bmn" href="<?php echo tbc_board_url('consult'); ?>">입학안내</a></li>
                                </ul>
                                <div class="right_img">
                                    <img src="<?php echo G5_THEME_URL; ?>/img/common/all_bg00.jpg" alt="메뉴배경">
                                    <div class="txt">ⓒ 더브레인코어</div>
                                </div>
                            </div>
                        </div>
                        <div class="cs">
                            <p class="tit">Contact us</p>
                            <p class="add">대전광역시 서구 둔산동</p>
                            <p class="tel"><?php echo TBC_TEL; ?></p>
                            <p class="etc">E-mail . info@thebraincore.co.kr</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 반응형메뉴 [s] -->
            <div id="topmenuM">
                <div id="m_logo"><a href="<?php echo G5_URL; ?>"><img src="<?php echo G5_THEME_URL; ?>/img/common/logo.png" alt="더브레인코어" /></a></div>
                <div id="m_navBtn"><span></span></div>
                <div id="navWrap">
                    <div class="inner">
                        <ul class="m_lnb">
                            <li>
                                <button class="m_bmenu" type="button">더브코</button>
                                <ul class="m_smenu">
                                    <li><a href="<?php echo tbc_page_url('greeting'); ?>">인사말</a></li>
                                    <li><a href="<?php echo tbc_page_url('philosophy'); ?>">교육철학</a></li>
                                    <li><a href="<?php echo tbc_page_url('history'); ?>">연혁</a></li>
                                </ul>
                            </li>
                            <li>
                                <button class="m_bmenu" type="button">관·분원</button>
                                <ul class="m_smenu">
                                    <li><a href="<?php echo tbc_page_url('academies'); ?>">전체 관·분원</a></li>
                                    <li><a href="<?php echo tbc_page_url('academies_main'); ?>">본원 · 대전 둔산</a></li>
                                    <li><a href="<?php echo tbc_page_url('academies_branch'); ?>">분원 안내</a></li>
                                </ul>
                            </li>
                            <li>
                                <button class="m_bmenu" type="button">강사진</button>
                                <ul class="m_smenu">
                                    <li><a href="<?php echo tbc_page_url('teachers'); ?>">전체 강사진</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_korean'); ?>">국어</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_math'); ?>">수학</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_science'); ?>">과학</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_english'); ?>">영어</a></li>
                                    <li><a href="<?php echo tbc_page_url('teachers_social'); ?>">사회</a></li>
                                </ul>
                            </li>
                            <li>
                                <button class="m_bmenu" type="button">시간표</button>
                                <ul class="m_smenu">
                                    <li><a href="<?php echo tbc_page_url('schedule'); ?>">전체 시간표</a></li>
                                    <li><a href="<?php echo tbc_page_url('schedule_main'); ?>">본원 시간표</a></li>
                                    <li><a href="<?php echo tbc_page_url('schedule_branch'); ?>">분원 시간표</a></li>
                                </ul>
                            </li>
                            <li>
                                <button class="m_bmenu" type="button">더브코 소식</button>
                                <ul class="m_smenu">
                                    <li><a href="<?php echo tbc_board_url('notice'); ?>">공지사항</a></li>
                                    <li><a href="<?php echo tbc_board_url('edu'); ?>">교육정보</a></li>
                                    <li><a href="<?php echo tbc_board_url('review'); ?>">수강후기</a></li>
                                </ul>
                            </li>
                            <li>
                                <button class="m_bmenu" type="button">입학안내</button>
                                <ul class="m_smenu">
                                    <li><a href="<?php echo tbc_board_url('consult'); ?>">상담신청</a></li>
                                    <li><a href="<?php echo tbc_page_url('admission'); ?>">입학절차</a></li>
                                    <li><a href="<?php echo tbc_page_url('faq'); ?>">FAQ</a></li>
                                </ul>
                            </li>
                        </ul>
                        <p class="mo_hd_copy">ⓒ 더브레인코어</p>
                    </div>
                </div>
            </div>
            <!-- 반응형메뉴 [e] -->
        </div>
    </header>
    <!-- sh_hd [e] -->
