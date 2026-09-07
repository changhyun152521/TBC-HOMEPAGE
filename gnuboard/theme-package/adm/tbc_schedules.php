<?php

$sub_menu = '950350';

require_once './_common.php';



if ($is_admin != 'super') {

    alert('최고관리자만 접근 가능합니다.');

}



auth_check_menu($auth, $sub_menu, 'r');



include_once(G5_THEME_PATH . '/tbc.schedule.lib.php');

tbc_schedule_ensure_tables();

tbc_schedule_seed_defaults();



$filter_grade = isset($_GET['grade']) ? preg_replace('/[^a-z0-9_]/', '', $_GET['grade']) : '';

$filter_subject = isset($_GET['subject']) ? preg_replace('/[^a-z_]/', '', $_GET['subject']) : '';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';



$sections = tbc_schedule_get_admin_grouped_list(array(

    'grade' => $filter_grade,

    'subject' => $filter_subject,

    'q' => $keyword,

));



$grades = tbc_schedule_grades();

$subjects = tbc_schedule_subjects();

$admin_token = get_admin_token();

$course_total = 0;



foreach ($sections as $section) {

    $course_total += count($section['courses']);

}



$g5['title'] = '시간표 관리';

$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';

include_once('./admin.head.php');

?>



<style>

.tbc-schedule-toolbar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin:16px 0 20px}

.tbc-schedule-filters{display:flex;flex-wrap:wrap;gap:8px;align-items:center}

.tbc-schedule-filters select,.tbc-schedule-filters input[type="text"]{height:34px}

.tbc-schedule-section{margin-bottom:28px}

.tbc-schedule-section-head{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between;margin:0 0 10px;padding:12px 14px;border:1px solid #dbeafe;border-radius:10px;background:#eff6ff}

.tbc-schedule-section-head strong{font-size:16px;color:#1e3a8a}

.tbc-schedule-section-meta{font-size:12px;color:#64748b}

.tbc-schedule-table{width:100%;border-collapse:collapse;background:#fff}

.tbc-schedule-table th,.tbc-schedule-table td{padding:12px 10px;border-bottom:1px solid #e2e8f0;vertical-align:middle;font-size:13px}

.tbc-schedule-table th{background:#f8fafc;color:#475569;text-align:left}

.tbc-schedule-thumb{width:56px;height:42px;border-radius:6px;object-fit:cover;border:1px solid #e2e8f0;background:#f1f5f9}

.tbc-schedule-order{display:flex;flex-direction:column;gap:4px;align-items:center}

.tbc-schedule-order button{width:28px;height:24px;border:1px solid #cbd5e1;border-radius:6px;background:#fff;cursor:pointer}

.tbc-schedule-order button:disabled{opacity:.35;cursor:not-allowed}

.tbc-schedule-badge{display:inline-block;padding:2px 8px;border-radius:4px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:600}

.tbc-schedule-badge.is-off{background:#fef2f2;color:#b91c1c}

.tbc-schedule-meta{color:#64748b;font-size:12px;margin-top:4px;line-height:1.5}

.tbc-schedule-actions{display:flex;gap:6px;flex-wrap:wrap}

.tbc-schedule-empty{padding:48px 20px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc}

.tbc-schedule-status{min-height:20px;margin-top:8px;font-size:13px;color:#2563eb}

.tbc-schedule-status.is-error{color:#dc2626}

</style>



<?php if ($admin_msg) { ?>

<div class="local_desc01 local_desc" style="margin-bottom:15px;">

    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>

</div>

<?php } ?>



<div class="local_desc01 local_desc">

    <p>관별로 강좌 시간표를 관리합니다. 각 관 안에서 ▲▼ 버튼으로 노출 순서를 조정할 수 있습니다.</p>

</div>



<div class="tbc-schedule-toolbar">

    <form method="get" class="tbc-schedule-filters">

        <select name="grade" class="frm_input">

            <option value="">전체 학년</option>

            <?php foreach ($grades as $code => $label) { ?>

            <option value="<?php echo $code; ?>"<?php echo $filter_grade === $code ? ' selected' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>

            <?php } ?>

        </select>

        <select name="subject" class="frm_input">

            <option value="">전체 과목</option>

            <?php foreach ($subjects as $code => $label) { ?>

            <option value="<?php echo $code; ?>"<?php echo $filter_subject === $code ? ' selected' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>

            <?php } ?>

        </select>

        <input type="text" name="q" value="<?php echo get_text($keyword); ?>" class="frm_input" placeholder="강좌명·강사 검색">

        <button type="submit" class="btn_frmline">검색</button>

    </form>

    <a href="./tbc_schedule_form.php" class="btn btn_03">강좌 등록</a>

</div>



<?php if (!$course_total) { ?>

<div class="tbc-schedule-empty">등록된 강좌가 없습니다. <strong>강좌 등록</strong> 버튼으로 시간표를 추가해 주세요.</div>

<?php } else { ?>

<?php foreach ($sections as $section) { ?>

<section class="tbc-schedule-section" data-academy="<?php echo htmlspecialchars($section['slug'], ENT_QUOTES, 'UTF-8'); ?>">

    <div class="tbc-schedule-section-head">

        <div>

            <strong><?php echo htmlspecialchars($section['name'], ENT_QUOTES, 'UTF-8'); ?></strong>

            <span class="tbc-schedule-section-meta"> — 총 <?php echo count($section['courses']); ?>개 강좌</span>

        </div>

        <a href="./tbc_schedule_form.php?academy=<?php echo urlencode($section['slug']); ?>" class="btn_frmline">이 관에 강좌 등록</a>

    </div>

    <div class="tbl_head01 tbl_wrap">

    <table class="tbc-schedule-table">

    <thead>

    <tr>

        <th scope="col" style="width:70px;">순서</th>

        <th scope="col" style="width:70px;">사진</th>

        <th scope="col">강좌</th>

        <th scope="col" style="width:100px;">학년·과목</th>

        <th scope="col">요일·시간</th>

        <th scope="col" style="width:100px;">수강료</th>

        <th scope="col" style="width:80px;">노출</th>

        <th scope="col" style="width:130px;">관리</th>

    </tr>

    </thead>

    <tbody>

    <?php

    $section_count = count($section['courses']);

    foreach ($section['courses'] as $index => $course) {

        $image_url = $course['sc_intro_image'] ? tbc_media_resolve_url('schedule', $course['sc_intro_image'], '') : '';

        $is_first = $index === 0;

        $is_last = $index === $section_count - 1;

    ?>

    <tr data-id="<?php echo (int) $course['sc_id']; ?>" data-academy="<?php echo htmlspecialchars($section['slug'], ENT_QUOTES, 'UTF-8'); ?>">

        <td>

            <div class="tbc-schedule-order">

                <button type="button" class="js-schedule-order" data-dir="up" title="위로"<?php echo $is_first ? ' disabled' : ''; ?>>▲</button>

                <button type="button" class="js-schedule-order" data-dir="down" title="아래로"<?php echo $is_last ? ' disabled' : ''; ?>>▼</button>

            </div>

        </td>

        <td>

            <?php if ($image_url) { ?>

            <img src="<?php echo $image_url; ?>" alt="" class="tbc-schedule-thumb">

            <?php } else { ?>

            <span class="tbc-schedule-meta">—</span>

            <?php } ?>

        </td>

        <td>

            <strong><?php echo htmlspecialchars($course['sc_name'], ENT_QUOTES, 'UTF-8'); ?></strong>

            <div class="tbc-schedule-meta">강사 <?php echo htmlspecialchars($course['sc_teacher'], ENT_QUOTES, 'UTF-8'); ?></div>

        </td>

        <td>

            <?php echo htmlspecialchars(tbc_schedule_grade_label($course['sc_grade']), ENT_QUOTES, 'UTF-8'); ?><br>

            <span class="tbc-schedule-meta"><?php echo htmlspecialchars(tbc_schedule_subject_label($course['sc_subject']), ENT_QUOTES, 'UTF-8'); ?></span>

        </td>

        <td><?php echo htmlspecialchars(tbc_schedule_format_slots_text($course['slots']), ENT_QUOTES, 'UTF-8'); ?></td>

        <td><?php echo htmlspecialchars($course['sc_fee'], ENT_QUOTES, 'UTF-8'); ?></td>

        <td>

            <span class="tbc-schedule-badge<?php echo $course['sc_use'] ? '' : ' is-off'; ?>"><?php echo $course['sc_use'] ? '노출' : '숨김'; ?></span>

        </td>

        <td>

            <div class="tbc-schedule-actions">

                <a href="./tbc_schedule_form.php?sc_id=<?php echo (int) $course['sc_id']; ?>" class="btn_frmline">수정</a>

                <button type="button" class="btn_frmline js-schedule-delete">삭제</button>

            </div>

        </td>

    </tr>

    <?php } ?>

    </tbody>

    </table>

    </div>

</section>

<?php } ?>

<?php } ?>



<div class="tbc-schedule-status" id="tbcScheduleStatus"></div>



<script>

(function () {

    var token = <?php echo json_encode($admin_token); ?>;

    var statusEl = document.getElementById('tbcScheduleStatus');



    function setStatus(msg, isError) {

        if (!statusEl) return;

        statusEl.textContent = msg || '';

        statusEl.className = 'tbc-schedule-status' + (isError ? ' is-error' : '');

    }



    function postAction(body, onDone) {

        var xhr = new XMLHttpRequest();

        xhr.open('POST', './tbc_schedule_ajax.php');

        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

        xhr.onload = function () {

            var res;

            try { res = JSON.parse(xhr.responseText); } catch (e) { setStatus('응답 처리 오류', true); return; }

            if (!res.ok) { setStatus(res.message || '요청 실패', true); return; }

            if (onDone) onDone(res);

        };

        xhr.send(body);

    }



    document.querySelectorAll('.js-schedule-order').forEach(function (btn) {

        btn.addEventListener('click', function () {

            if (btn.disabled) return;

            var row = btn.closest('tr');

            var id = row.getAttribute('data-id');

            postAction('token=' + encodeURIComponent(token) + '&action=move&sc_id=' + encodeURIComponent(id) + '&dir=' + encodeURIComponent(btn.getAttribute('data-dir')), function () {

                location.reload();

            });

        });

    });



    document.querySelectorAll('.js-schedule-delete').forEach(function (btn) {

        btn.addEventListener('click', function () {

            if (!confirm('이 강좌를 삭제할까요?')) return;

            var row = btn.closest('tr');

            var id = row.getAttribute('data-id');

            postAction('token=' + encodeURIComponent(token) + '&action=delete&sc_id=' + encodeURIComponent(id), function () {

                row.remove();

                setStatus('삭제되었습니다.');

            });

        });

    });

})();

</script>



<?php

include_once('./admin.tail.php');


