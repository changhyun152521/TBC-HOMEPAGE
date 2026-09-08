<?php
$sub_menu = '950350';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

include_once(G5_THEME_PATH . '/tbc.schedule.lib.php');
tbc_schedule_ensure_tables();

$sc_id = isset($_GET['sc_id']) ? (int) $_GET['sc_id'] : 0;
$prefill_academy = isset($_GET['academy']) ? preg_replace('/[^a-z_]/', '', $_GET['academy']) : '';
$course = $sc_id ? tbc_schedule_get($sc_id) : null;

if ($sc_id && !$course) {
    alert('강좌 정보를 찾을 수 없습니다.', './tbc_schedules.php');
}

$academies = tbc_schedule_academy_catalog();
$grades = tbc_schedule_grades();
$subjects = tbc_schedule_subjects();
$days = tbc_schedule_days();
$admin_token = get_admin_token();
$is_edit = (bool) $course;

$g5['title'] = $is_edit ? '강좌 수정' : '강좌 등록';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');

$form = $course ? $course : array(
    'sc_academy' => $prefill_academy && isset($academies[$prefill_academy]) ? $prefill_academy : 'high',
    'sc_grade' => 'h1',
    'sc_subject' => 'math',
    'sc_name' => '',
    'sc_teacher' => '',
    'sc_fee' => '',
    'sc_use' => 1,
    'sc_intro_image' => '',
    'slots' => array(
        array('sl_day' => 'mon', 'sl_start' => '', 'sl_end' => ''),
    ),
);

$image_preview_url = '';
if (!empty($form['sc_intro_image'])) {
    $image_preview_url = tbc_media_resolve_url('schedule', $form['sc_intro_image'], '');
}
?>

<style>
.tbc-form-intro{margin-bottom:18px}
.tbc-form-section{margin-top:24px}
.tbc-form-title{margin:0 0 8px;font-size:16px;font-weight:700;color:#1e293b}
.tbc-form-desc{margin:0 0 12px;color:#64748b;font-size:13px}
.tbc-slot-list{display:flex;flex-direction:column;gap:10px;max-width:640px}
.tbc-slot-row{display:grid;grid-template-columns:90px 1fr 1fr auto;gap:8px;align-items:center}
.tbc-slot-row select,.tbc-slot-row input{width:100%}
.tbc-slot-remove{width:34px;height:34px;border:1px solid #fecaca;border-radius:6px;background:#fff;color:#dc2626;cursor:pointer}
.tbc-form-preview img{display:block;max-width:240px;border-radius:10px;border:1px solid #e2e8f0}
.tbc-form-preview-empty{padding:24px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:10px;background:#f8fafc;max-width:240px}
@media (max-width:768px){.tbc-slot-row{grid-template-columns:1fr 1fr;}.tbc-slot-row select{grid-column:1/-1}.tbc-slot-remove{grid-column:1/-1}}
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc tbc-form-intro">
    <p>강좌 정보와 요일별 강의 시간을 입력하고 맨 아래 <strong>「저장」</strong> 버튼을 눌러주세요.</p>
</div>

<form name="ftbcschedule" id="ftbcschedule" action="./tbc_schedule_update.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">
<input type="hidden" name="sc_id" value="<?php echo (int) $sc_id; ?>">

<div class="tbl_frm01 tbl_wrap tbc-form-section">
    <h2 class="tbc-form-title">① 분류</h2>
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="sc_academy">관</label></th>
        <td>
            <select name="sc_academy" id="sc_academy" class="frm_input" required>
                <?php foreach ($academies as $slug => $info) { ?>
                <option value="<?php echo $slug; ?>"<?php echo $form['sc_academy'] === $slug ? ' selected' : ''; ?>><?php echo htmlspecialchars($info['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="sc_grade">학년</label></th>
        <td>
            <select name="sc_grade" id="sc_grade" class="frm_input" required>
                <?php foreach ($grades as $code => $label) { ?>
                <option value="<?php echo $code; ?>"<?php echo $form['sc_grade'] === $code ? ' selected' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="sc_subject">과목</label></th>
        <td>
            <select name="sc_subject" id="sc_subject" class="frm_input" required>
                <?php foreach ($subjects as $code => $label) { ?>
                <option value="<?php echo $code; ?>"<?php echo $form['sc_subject'] === $code ? ' selected' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    </tbody>
    </table>
</div>

<div class="tbl_frm01 tbl_wrap tbc-form-section">
    <h2 class="tbc-form-title">② 강좌 정보</h2>
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="sc_name">강좌명</label></th>
        <td><input type="text" name="sc_name" id="sc_name" value="<?php echo get_text($form['sc_name']); ?>" class="frm_input" style="width:100%;max-width:480px;" required placeholder="예: 고1 수학 정규반"></td>
    </tr>
    <tr>
        <th scope="row"><label for="sc_teacher">강사</label></th>
        <td><input type="text" name="sc_teacher" id="sc_teacher" value="<?php echo get_text($form['sc_teacher']); ?>" class="frm_input" style="width:100%;max-width:240px;" placeholder="예: 김나영"></td>
    </tr>
    <tr>
        <th scope="row"><label for="sc_fee">수강료</label></th>
        <td><input type="text" name="sc_fee" id="sc_fee" value="<?php echo get_text($form['sc_fee']); ?>" class="frm_input" style="width:100%;max-width:240px;" placeholder="예: 월 350,000원"></td>
    </tr>
    <tr>
        <th scope="row">노출</th>
        <td><label><input type="checkbox" name="sc_use" value="1"<?php echo !empty($form['sc_use']) ? ' checked' : ''; ?>> 사이트에 표시</label></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="tbl_frm01 tbl_wrap tbc-form-section">
    <h2 class="tbc-form-title">③ 요일·강의 시간</h2>
    <p class="tbc-form-desc">요일마다 강의 시간이 다를 수 있습니다. 필요한 만큼 행을 추가해 주세요.</p>
    <div class="tbc-slot-list" id="tbcSlotList">
        <?php
        $slots = !empty($form['slots']) ? $form['slots'] : array(array('sl_day' => 'mon', 'sl_start' => '', 'sl_end' => ''));
        foreach ($slots as $index => $slot) {
        ?>
        <div class="tbc-slot-row">
            <select name="slots[<?php echo (int) $index; ?>][day]" required>
                <?php foreach ($days as $code => $label) { ?>
                <option value="<?php echo $code; ?>"<?php echo $slot['sl_day'] === $code ? ' selected' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php } ?>
            </select>
            <input type="text" name="slots[<?php echo (int) $index; ?>][start]" value="<?php echo get_text($slot['sl_start']); ?>" placeholder="시작 18:00" pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$">
            <input type="text" name="slots[<?php echo (int) $index; ?>][end]" value="<?php echo get_text($slot['sl_end']); ?>" placeholder="종료 21:00" pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$">
            <button type="button" class="tbc-slot-remove" title="삭제">×</button>
        </div>
        <?php } ?>
    </div>
    <p style="margin-top:12px;"><button type="button" class="btn_frmline" id="tbcSlotAdd">+ 요일 추가</button></p>
</div>

<div class="tbl_frm01 tbl_wrap tbc-form-section">
    <h2 class="tbc-form-title">④ 강좌 소개 사진</h2>
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row">미리보기</th>
        <td class="tbc-form-preview">
            <?php if ($image_preview_url) { ?>
            <img src="<?php echo $image_preview_url; ?>" alt="">
            <?php } else { ?>
            <div class="tbc-form-preview-empty">등록된 사진 없음</div>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="schedule_image">사진 업로드</label></th>
        <td>
            <input type="file" name="schedule_image" id="schedule_image" accept="image/jpeg,image/png,image/webp,image/gif">
            <p class="frm_info">jpg, png, webp, gif · 5MB 이하</p>
        </td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm" style="margin-top:24px;">
    <input type="submit" value="저장" class="btn_submit btn">
    <a href="./tbc_schedules.php" class="btn_cancel btn">목록</a>
</div>
</form>

<script>
(function () {
    var list = document.getElementById('tbcSlotList');
    var addBtn = document.getElementById('tbcSlotAdd');
    var dayOptions = <?php
        $opts = array();
        foreach ($days as $code => $label) {
            $opts[] = array('value' => $code, 'label' => $label);
        }
        echo json_encode($opts, JSON_UNESCAPED_UNICODE);
    ?>;

    function reindexRows() {
        var rows = list.querySelectorAll('.tbc-slot-row');
        rows.forEach(function (row, index) {
            row.querySelectorAll('[name]').forEach(function (input) {
                input.name = input.name.replace(/slots\[\d+\]/, 'slots[' + index + ']');
            });
        });
    }

    function createRow() {
        var row = document.createElement('div');
        row.className = 'tbc-slot-row';
        var select = '<select name="slots[0][day]" required>';
        dayOptions.forEach(function (opt) {
            select += '<option value="' + opt.value + '">' + opt.label + '</option>';
        });
        select += '</select>';
        row.innerHTML = select +
            '<input type="text" name="slots[0][start]" placeholder="시작 18:00">' +
            '<input type="text" name="slots[0][end]" placeholder="종료 21:00">' +
            '<button type="button" class="tbc-slot-remove" title="삭제">×</button>';
        return row;
    }

    if (addBtn) {
        addBtn.addEventListener('click', function () {
            list.appendChild(createRow());
            reindexRows();
        });
    }

    list.addEventListener('click', function (e) {
        if (!e.target.classList.contains('tbc-slot-remove')) return;
        var rows = list.querySelectorAll('.tbc-slot-row');
        if (rows.length <= 1) {
            rows[0].querySelectorAll('input').forEach(function (input) { input.value = ''; });
            return;
        }
        e.target.closest('.tbc-slot-row').remove();
        reindexRows();
    });
})();
</script>

<?php
include_once('./admin.tail.php');
