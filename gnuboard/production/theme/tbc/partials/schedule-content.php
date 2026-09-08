<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_schedule_build_public_payload')) {
    include_once(G5_THEME_PATH . '/tbc.schedule.lib.php');
}

tbc_schedule_ensure_tables();
tbc_schedule_seed_defaults();

$title = isset($tbc_schedule_title) ? $tbc_schedule_title : '시간표';
$desc = isset($tbc_schedule_desc) ? $tbc_schedule_desc : '전체 강좌 목록을 확인하고, 관·학년·과목 필터로 원하는 강좌를 찾을 수 있습니다.';
$default_academy = isset($tbc_schedule_academy) ? $tbc_schedule_academy : '';
$schedule_group = isset($tbc_schedule_group) ? $tbc_schedule_group : '';
if ($schedule_group !== 'main' && $schedule_group !== 'branch') {
    $schedule_group = '';
}
$payload = tbc_schedule_build_public_payload($schedule_group);
?>
<div id="schedule1001" class="pagecm" data-academy="<?php echo htmlspecialchars($default_academy, ENT_QUOTES, 'UTF-8'); ?>">
    <div class="tit_area sch_tit_area">
        <span>SCHEDULE</span>
        <p><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="pl"><?php echo htmlspecialchars($desc, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>

    <div class="sch_filter">
        <div class="sch_filter_row">
            <strong class="sch_filter_label">관</strong>
            <div class="sch_chip_list" id="schAcademyList"></div>
        </div>
        <div class="sch_filter_row">
            <strong class="sch_filter_label">학년</strong>
            <div class="sch_chip_list" id="schGradeList"></div>
        </div>
        <div class="sch_filter_row">
            <strong class="sch_filter_label">과목</strong>
            <div class="sch_chip_list" id="schSubjectList"></div>
        </div>
    </div>

    <div class="tbl_area" id="schTableWrap">
        <table cellpadding="0" cellspacing="0">
            <caption class="sound_only">강좌 시간표</caption>
            <thead>
                <tr>
                    <th scope="col" class="sch_col_grade">학년</th>
                    <th scope="col" class="sch_col_subject">과목</th>
                    <th scope="col" class="sch_col_name">강좌명</th>
                    <th scope="col" class="sch_col_place">장소</th>
                    <th scope="col" class="sch_col_teacher">강사</th>
                    <th scope="col" class="sch_col_time">요일 · 시간</th>
                    <th scope="col" class="sch_col_fee">수강료</th>
                    <th scope="col" class="sch_col_intro">강좌 소개</th>
                </tr>
            </thead>
            <tbody id="schTableBody"></tbody>
        </table>
    </div>

    <div class="sch_empty" id="schEmpty" style="display:none;">
        <strong>조건에 맞는 강좌가 없습니다</strong>
        <p>다른 관·학년·과목을 선택해 보세요.</p>
    </div>
</div>
<script>
window.TBC_SCHEDULE_DATA = <?php echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
</script>
<script src="<?php echo G5_THEME_URL; ?>/js/schedule.js?v=<?php echo @filemtime(G5_THEME_PATH . '/js/schedule.js'); ?>"></script>
