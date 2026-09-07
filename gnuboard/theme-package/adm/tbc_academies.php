<?php
$sub_menu = '950250';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'r');

include_once(G5_THEME_PATH . '/tbc.academy.lib.php');
tbc_academy_ensure_tables();
tbc_academy_upgrade_schema();
tbc_academy_seed_defaults();
tbc_academy_normalize_orders();

$filter_type = isset($_GET['type']) ? preg_replace('/[^a-z]/', '', $_GET['type']) : '';
if ($filter_type !== 'main' && $filter_type !== 'branch') {
    $filter_type = '';
}

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$academies = tbc_academy_get_list($filter_type, $keyword);
$types = tbc_academy_types();
$admin_token = get_admin_token();

$g5['title'] = '분원 관리';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');
?>

<style>
.tbc-academy-toolbar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin:16px 0 20px}
.tbc-academy-tabs{display:flex;flex-wrap:wrap;gap:6px}
.tbc-academy-tabs a{display:inline-block;padding:8px 14px;border:1px solid #cbd5e1;border-radius:999px;background:#fff;color:#334155;font-size:13px;text-decoration:none}
.tbc-academy-tabs a.is-active{background:#2563eb;border-color:#2563eb;color:#fff;font-weight:700}
.tbc-academy-search{display:flex;gap:8px;align-items:center}
.tbc-academy-search input[type="text"]{width:220px}
.tbc-academy-table{width:100%;border-collapse:collapse;background:#fff}
.tbc-academy-table th,.tbc-academy-table td{padding:12px 10px;border-bottom:1px solid #e2e8f0;vertical-align:middle;font-size:13px}
.tbc-academy-table th{background:#f8fafc;color:#475569;text-align:left}
.tbc-academy-thumb{width:72px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #e2e8f0;background:#f1f5f9}
.tbc-academy-order{display:flex;flex-direction:column;gap:4px;align-items:center}
.tbc-academy-order-num{width:28px;height:28px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;margin:0 auto 4px}
.tbc-academy-order button{width:28px;height:24px;border:1px solid #cbd5e1;border-radius:6px;background:#fff;cursor:pointer}
.tbc-academy-order button:disabled{opacity:.35;cursor:not-allowed}
.tbc-academy-name{font-weight:700;color:#0f172a}
.tbc-academy-meta{color:#64748b;font-size:12px;margin-top:4px}
.tbc-academy-badge{display:inline-block;padding:2px 8px;border-radius:999px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:600}
.tbc-academy-badge.is-branch{background:#f0fdf4;color:#15803d}
.tbc-academy-badge.is-off{background:#fef2f2;color:#b91c1c}
.tbc-academy-actions{display:flex;gap:6px;flex-wrap:wrap}
.tbc-academy-status{min-height:20px;margin-top:8px;font-size:13px;color:#2563eb}
.tbc-academy-status.is-error{color:#dc2626}
.tbc-academy-empty{padding:48px 20px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc}
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc">
    <p>분원 메뉴에 표시되는 <strong>본원·분원</strong> 정보를 추가·수정·삭제할 수 있습니다.<br>
    <strong>전체</strong> 탭에서 ▲▼ 버튼으로 노출 순서를 바꿀 수 있습니다.</p>
</div>

<div class="tbc-academy-toolbar">
    <div class="tbc-academy-tabs">
        <a href="./tbc_academies.php" class="<?php echo $filter_type === '' ? 'is-active' : ''; ?>">전체</a>
        <?php foreach ($types as $code => $label) { ?>
        <a href="./tbc_academies.php?type=<?php echo urlencode($code); ?>" class="<?php echo $filter_type === $code ? 'is-active' : ''; ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></a>
        <?php } ?>
    </div>
    <form class="tbc-academy-search" method="get" action="./tbc_academies.php">
        <?php if ($filter_type !== '') { ?>
        <input type="hidden" name="type" value="<?php echo htmlspecialchars($filter_type, ENT_QUOTES, 'UTF-8'); ?>">
        <?php } ?>
        <input type="text" name="q" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" class="frm_input" placeholder="이름·지역·주소 검색">
        <input type="submit" value="검색" class="btn btn_02">
        <a href="./tbc_academy_form.php<?php echo $filter_type ? '?type=' . urlencode($filter_type) : ''; ?>" class="btn btn_01">+ 분원 추가</a>
    </form>
</div>

<p id="tbc-academy-status" class="tbc-academy-status" aria-live="polite"></p>

<?php if (!$academies) { ?>
<div class="tbc-academy-empty">
    <?php echo $keyword ? '검색 결과가 없습니다.' : '등록된 분원이 없습니다. 「분원 추가」 버튼으로 등록해 주세요.'; ?>
</div>
<?php } else { ?>
<div class="tbl_head01 tbl_wrap">
<table class="tbc-academy-table">
<thead>
<tr>
    <th scope="col" style="width:56px;">순서</th>
    <th scope="col" style="width:88px;">사진</th>
    <th scope="col">분원 정보</th>
    <th scope="col" style="width:72px;">구분</th>
    <th scope="col" style="width:72px;">노출</th>
    <th scope="col" style="width:140px;">관리</th>
</tr>
</thead>
<tbody id="tbc-academy-list">
<?php foreach ($academies as $i => $academy) { ?>
<tr data-ac-id="<?php echo (int) $academy['ac_id']; ?>">
    <td>
        <div class="tbc-academy-order">
            <span class="tbc-academy-order-num"><?php echo $i + 1; ?></span>
            <button type="button" class="tbc-academy-up" title="위로"<?php echo ($keyword || $filter_type) ? ' disabled' : ''; ?>>▲</button>
            <button type="button" class="tbc-academy-down" title="아래로"<?php echo ($keyword || $filter_type) ? ' disabled' : ''; ?>>▼</button>
        </div>
    </td>
    <td><img src="<?php echo $academy['image_url']; ?>" alt="" class="tbc-academy-thumb"></td>
    <td>
        <div class="tbc-academy-name"><?php echo htmlspecialchars($academy['ac_name'], ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="tbc-academy-meta"><?php echo htmlspecialchars($academy['ac_region'], ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="tbc-academy-meta"><?php echo htmlspecialchars($academy['ac_address'], ENT_QUOTES, 'UTF-8'); ?></div>
    </td>
    <td>
        <span class="tbc-academy-badge<?php echo $academy['ac_type'] === 'branch' ? ' is-branch' : ''; ?>">
            <?php echo htmlspecialchars($academy['type_name'], ENT_QUOTES, 'UTF-8'); ?>
        </span>
    </td>
    <td>
        <span class="tbc-academy-badge<?php echo $academy['ac_use'] ? '' : ' is-off'; ?>">
            <?php echo $academy['ac_use'] ? '노출' : '숨김'; ?>
        </span>
    </td>
    <td>
        <div class="tbc-academy-actions">
            <a href="./tbc_academy_form.php?ac_id=<?php echo (int) $academy['ac_id']; ?>" class="btn btn_03">수정</a>
            <button type="button" class="btn btn_02 tbc-academy-delete">삭제</button>
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
    var ajaxUrl = './tbc_academy_ajax.php';
    var adminToken = <?php echo json_encode($admin_token); ?>;
    var listScope = <?php echo json_encode($filter_type); ?>;
    var canReorder = <?php echo ($keyword || $filter_type) ? 'false' : 'true'; ?>;
    var $list = $('#tbc-academy-list');
    var $status = $('#tbc-academy-status');

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
            $(this).find('.tbc-academy-order-num').text(index + 1);
            if (!canReorder) {
                $(this).find('.tbc-academy-up, .tbc-academy-down').prop('disabled', true);
                return;
            }
            $(this).find('.tbc-academy-up').prop('disabled', index === 0);
            $(this).find('.tbc-academy-down').prop('disabled', index === rows.length - 1);
        });
    }

    $list.on('click', '.tbc-academy-up, .tbc-academy-down', function() {
        if (!canReorder || $(this).prop('disabled')) return;
        var $row = $(this).closest('tr');
        var acId = $row.data('ac-id');
        var direction = $(this).hasClass('tbc-academy-up') ? 'up' : 'down';
        var $target = direction === 'up' ? $row.prev('tr') : $row.next('tr');
        if (!$target.length) return;

        if (direction === 'up') $row.insertBefore($target);
        else $row.insertAfter($target);
        updateOrderButtons();
        setStatus('순서 변경 중...');

        postAction({ action: 'move_academy', ac_id: acId, direction: direction, scope: listScope }, function() {
            setStatus('순서가 변경되었습니다.');
        }, function(message) {
            if (direction === 'up') $row.insertAfter($target);
            else $row.insertBefore($target);
            updateOrderButtons();
            setStatus(message, true);
        });
    });

    $list.on('click', '.tbc-academy-delete', function() {
        var $row = $(this).closest('tr');
        var acId = $row.data('ac-id');
        if (!window.confirm('이 분원을 삭제하시겠습니까?')) return;

        setStatus('삭제 중...');
        postAction({ action: 'delete_academy', ac_id: acId }, function() {
            $row.remove();
            updateOrderButtons();
            setStatus('분원이 삭제되었습니다.');
            if (!$list.find('tr').length) window.location.reload();
        }, function(message) {
            setStatus(message, true);
        });
    });

    updateOrderButtons();
})(jQuery);
</script>

<?php include_once('./admin.tail.php'); ?>
