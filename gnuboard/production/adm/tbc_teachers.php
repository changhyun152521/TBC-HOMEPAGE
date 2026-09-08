<?php

$sub_menu = '950300';

require_once './_common.php';



auth_check_menu($auth, $sub_menu, 'r');



include_once(G5_THEME_PATH . '/tbc.teacher.lib.php');

tbc_teacher_ensure_tables();

tbc_teacher_seed_defaults();

tbc_teacher_repair_global_orders();



$filter_subject = isset($_GET['subject']) ? preg_replace('/[^a-z]/', '', $_GET['subject']) : '';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

$teachers = tbc_teacher_get_admin_list($filter_subject, $keyword);

$subjects = tbc_teacher_subjects();

$admin_token = get_admin_token();



$g5['title'] = '강사진 관리';

$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';

include_once('./admin.head.php');

?>



<style>

.tbc-teacher-toolbar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin:16px 0 20px}

.tbc-teacher-tabs{display:flex;flex-wrap:wrap;gap:6px}

.tbc-teacher-tabs a{display:inline-block;padding:8px 14px;border:1px solid #cbd5e1;border-radius:999px;background:#fff;color:#334155;font-size:13px;text-decoration:none}

.tbc-teacher-tabs a.is-active{background:#2563eb;border-color:#2563eb;color:#fff;font-weight:700}

.tbc-teacher-search{display:flex;gap:8px;align-items:center}

.tbc-teacher-search input[type="text"]{width:220px}

.tbc-teacher-table{width:100%;border-collapse:collapse;background:#fff}

.tbc-teacher-table th,.tbc-teacher-table td{padding:12px 10px;border-bottom:1px solid #e2e8f0;vertical-align:middle;font-size:13px}

.tbc-teacher-table th{background:#f8fafc;color:#475569;text-align:left}

.tbc-teacher-thumb{width:52px;height:52px;border-radius:8px;object-fit:cover;border:1px solid #e2e8f0;background:#f1f5f9}

.tbc-teacher-order-num{width:28px;height:28px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;margin:0 auto 4px}

.tbc-teacher-order{display:flex;flex-direction:column;gap:4px;align-items:center}

.tbc-teacher-order button{width:28px;height:24px;border:1px solid #cbd5e1;border-radius:6px;background:#fff;cursor:pointer}

.tbc-teacher-order button:disabled{opacity:.35;cursor:not-allowed}

.tbc-teacher-name{font-weight:700;color:#0f172a}

.tbc-teacher-meta{color:#64748b;font-size:12px;margin-top:4px}

.tbc-teacher-badge{display:inline-block;padding:2px 8px;border-radius:999px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:600}

.tbc-teacher-badge.is-off{background:#fef2f2;color:#b91c1c}

.tbc-teacher-actions{display:flex;gap:6px;flex-wrap:wrap}

.tbc-teacher-status{min-height:20px;margin-top:8px;font-size:13px;color:#2563eb}

.tbc-teacher-status.is-error{color:#dc2626}

.tbc-teacher-empty{padding:48px 20px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc}

</style>



<?php if ($admin_msg) { ?>

<div class="local_desc01 local_desc" style="margin-bottom:15px;">

    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>

</div>

<?php } ?>



<div class="local_desc01 local_desc">

    <p>강사진 메뉴에 표시되는 강사를 추가·수정·삭제할 수 있습니다.<br>

    <strong>전체</strong> 탭에서 ▲▼ 버튼으로 강사진 페이지에 보이는 순서를 자유롭게 바꿀 수 있습니다.</p>

</div>



<div class="tbc-teacher-toolbar">

    <div class="tbc-teacher-tabs">

        <a href="./tbc_teachers.php" class="<?php echo $filter_subject === '' ? 'is-active' : ''; ?>">전체</a>

        <?php foreach ($subjects as $code => $label) { ?>

        <a href="./tbc_teachers.php?subject=<?php echo urlencode($code); ?>" class="<?php echo $filter_subject === $code ? 'is-active' : ''; ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></a>

        <?php } ?>

    </div>

    <form class="tbc-teacher-search" method="get" action="./tbc_teachers.php">

        <?php if ($filter_subject !== '') { ?>

        <input type="hidden" name="subject" value="<?php echo htmlspecialchars($filter_subject, ENT_QUOTES, 'UTF-8'); ?>">

        <?php } ?>

        <input type="text" name="q" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" class="frm_input" placeholder="이름·과목·한줄소개 검색">

        <input type="submit" value="검색" class="btn btn_02">

        <a href="./tbc_teacher_form.php<?php echo $filter_subject ? '?subject=' . urlencode($filter_subject) : ''; ?>" class="btn btn_01">+ 강사 추가</a>

    </form>

</div>



<p id="tbc-teacher-status" class="tbc-teacher-status" aria-live="polite"></p>



<?php if (!$teachers) { ?>

<div class="tbc-teacher-empty">

    <?php echo $keyword ? '검색 결과가 없습니다.' : '등록된 강사가 없습니다. 「강사 추가」 버튼으로 등록해 주세요.'; ?>

</div>

<?php } else { ?>

<div class="tbl_head01 tbl_wrap">

<table class="tbc-teacher-table">

<thead>

<tr>

    <th scope="col" style="width:56px;">순서</th>

    <th scope="col" style="width:72px;">사진</th>

    <th scope="col">강사 정보</th>

    <th scope="col" style="width:88px;">과목</th>

    <th scope="col" style="width:110px;">자료</th>

    <th scope="col" style="width:72px;">노출</th>

    <th scope="col" style="width:140px;">관리</th>

</tr>

</thead>

<tbody id="tbc-teacher-list">

<?php foreach ($teachers as $i => $teacher) { ?>

<tr data-tc-id="<?php echo (int) $teacher['tc_id']; ?>">

    <td>

        <div class="tbc-teacher-order">

            <span class="tbc-teacher-order-num"><?php echo $i + 1; ?></span>

            <button type="button" class="tbc-teacher-up" title="위로"<?php echo ($keyword || $filter_subject) ? ' disabled' : ''; ?>>▲</button>

            <button type="button" class="tbc-teacher-down" title="아래로"<?php echo ($keyword || $filter_subject) ? ' disabled' : ''; ?>>▼</button>

        </div>

    </td>

    <td><img src="<?php echo $teacher['profile_url']; ?>" alt="" class="tbc-teacher-thumb"></td>

    <td>

        <div class="tbc-teacher-name"><?php echo htmlspecialchars($teacher['tc_name'], ENT_QUOTES, 'UTF-8'); ?></div>

        <div class="tbc-teacher-meta"><?php echo htmlspecialchars($teacher['tc_subject_label'], ENT_QUOTES, 'UTF-8'); ?></div>

        <div class="tbc-teacher-meta"><?php echo htmlspecialchars($teacher['tc_tagline'], ENT_QUOTES, 'UTF-8'); ?></div>

    </td>

    <td><?php echo htmlspecialchars($teacher['subject_name'], ENT_QUOTES, 'UTF-8'); ?></td>

    <td>

        커리 <?php echo (int) $teacher['curriculum_count']; ?>장<br>

        소개 <?php echo (int) $teacher['intro_count']; ?>장

    </td>

    <td>

        <span class="tbc-teacher-badge<?php echo $teacher['tc_use'] ? '' : ' is-off'; ?>">

            <?php echo $teacher['tc_use'] ? '노출' : '숨김'; ?>

        </span>

    </td>

    <td>

        <div class="tbc-teacher-actions">

            <a href="./tbc_teacher_form.php?tc_id=<?php echo (int) $teacher['tc_id']; ?>" class="btn btn_03">수정</a>

            <button type="button" class="btn btn_02 tbc-teacher-delete">삭제</button>

        </div>

    </td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<?php } ?>



<script>

(function($) {

    var ajaxUrl = './tbc_teacher_ajax.php';

    var adminToken = <?php echo json_encode($admin_token); ?>;

    var listScope = <?php echo json_encode($filter_subject); ?>;

    var canReorder = <?php echo ($keyword || $filter_subject) ? 'false' : 'true'; ?>;

    var $list = $('#tbc-teacher-list');

    var $status = $('#tbc-teacher-status');



    function setStatus(message, isError) {

        $status.text(message || '').toggleClass('is-error', !!isError);

        if (message && !isError) {

            window.setTimeout(function() {

                if ($status.text() === message) $status.text('');

            }, 2000);

        }

    }



    function postAction(data, onSuccess, onFail) {

        data.token = adminToken;

        $.ajax({

            url: ajaxUrl,

            method: 'POST',

            dataType: 'json',

            data: data

        }).done(function(res) {

            if (!res || !res.ok) {

                onFail(res && res.message ? res.message : '요청에 실패했습니다.');

                return;

            }

            onSuccess(res);

        }).fail(function() {

            onFail('서버 통신에 실패했습니다.');

        });

    }



    function updateOrderButtons() {

        var rows = $list.find('tr');

        rows.each(function(index) {

            $(this).find('.tbc-teacher-order-num').text(index + 1);

            if (!canReorder) {

                $(this).find('.tbc-teacher-up, .tbc-teacher-down').prop('disabled', true);

                return;

            }

            $(this).find('.tbc-teacher-up').prop('disabled', index === 0);

            $(this).find('.tbc-teacher-down').prop('disabled', index === rows.length - 1);

        });

    }



    $list.on('click', '.tbc-teacher-up, .tbc-teacher-down', function() {

        if (!canReorder || $(this).prop('disabled')) {

            return;

        }

        var $row = $(this).closest('tr');

        var tcId = $row.data('tc-id');

        var direction = $(this).hasClass('tbc-teacher-up') ? 'up' : 'down';

        var $target = direction === 'up' ? $row.prev('tr') : $row.next('tr');



        if (!$target.length) {

            return;

        }



        if (direction === 'up') {

            $row.insertBefore($target);

        } else {

            $row.insertAfter($target);

        }

        updateOrderButtons();

        setStatus('순서 변경 중...');



        postAction({ action: 'move_teacher', tc_id: tcId, direction: direction, scope: listScope }, function() {

            setStatus('순서가 변경되었습니다.');

        }, function(message) {

            if (direction === 'up') {

                $row.insertAfter($target);

            } else {

                $row.insertBefore($target);

            }

            updateOrderButtons();

            setStatus(message, true);

        });

    });



    $list.on('click', '.tbc-teacher-delete', function() {

        var $row = $(this).closest('tr');

        var tcId = $row.data('tc-id');

        if (!window.confirm('이 강사를 삭제하시겠습니까?\n프로필·커리큘럼·소개 사진이 모두 삭제됩니다.')) {

            return;

        }



        setStatus('삭제 중...');

        postAction({ action: 'delete_teacher', tc_id: tcId }, function() {

            $row.remove();

            updateOrderButtons();

            setStatus('강사가 삭제되었습니다.');

            if (!$list.find('tr').length) {

                window.location.reload();

            }

        }, function(message) {

            setStatus(message, true);

        });

    });



    updateOrderButtons();

})(jQuery);

</script>



<?php

include_once('./admin.tail.php');

