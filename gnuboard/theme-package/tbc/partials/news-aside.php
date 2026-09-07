<?php
if (!defined('_GNUBOARD_')) exit;

if (!isset($tbc_news_nav)) {
    $tbc_news_nav = 'notice';
}
?>
<div id="sh_aside">
    <div id="sh_aside_wrapper">
        <ul id="shSnb">
            <li<?php echo $tbc_news_nav === 'notice' ? ' class="on"' : ''; ?>>
                <a href="<?php echo tbc_page_url('notice'); ?>">공지사항</a>
            </li>
            <li<?php echo $tbc_news_nav === 'edu' ? ' class="on"' : ''; ?>>
                <a href="<?php echo tbc_page_url('edu'); ?>">교육정보</a>
            </li>
            <li<?php echo $tbc_news_nav === 'review' ? ' class="on"' : ''; ?>>
                <a href="<?php echo tbc_board_url('review'); ?>">수강후기</a>
            </li>
        </ul>
    </div>
</div>
