<?php
$sub_menu = '950390';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'r');

include_once(G5_THEME_PATH . '/tbc.consult.lib.php');
include_once(G5_THEME_PATH . '/tbc.academy.lib.php');
tbc_consult_ensure_tables();
tbc_academy_ensure_tables();
tbc_consult_normalize_orders();

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$ac_id = isset($_GET['ac_id']) ? (int) $_GET['ac_id'] : 0;
$forms = tbc_consult_get_list(array(
    'q' => $keyword,
    'ac_id' => $ac_id,
));
$academies = tbc_academy_get_list('', '', false);
$admin_token = get_admin_token();

$g5['title'] = '상담신청(구글폼) 관리';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');
?>

<style>
.tbc-consult-toolbar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin:16px 0 20px}
.tbc-consult-search{display:flex;flex-wrap:wrap;gap:8px;align-items:center}
.tbc-consult-search select,.tbc-consult-search input[type="text"]{height:34px}
.tbc-consult-search select{min-width:180px}
.tbc-consult-search input[type="text"]{width:220px}
.tbc-consult-table{width:100%;border-collapse:collapse;background:#fff}
.tbc-consult-table th,.tbc-consult-table td{padding:12px 10px;border-bottom:1px solid #e2e8f0;vertical-align:middle;font-size:13px}
.tbc-consult-table th{background:#f8fafc;color:#475569;text-align:left}
.tbc-consult-order{display:flex;flex-direction:column;gap:4px;align-items:center}
.tbc-consult-order-num{width:28px;height:28px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700}
.tbc-consult-order button{width:28px;height:24px;border:1px solid #cbd5e1;border-radius:6px;background:#fff;cursor:pointer}
.tbc-consult-order button:disabled{opacity:.35;cursor:not-allowed}
.tbc-consult-url{color:#64748b;font-size:12px;word-break:break-all}
.tbc-consult-badge{display:inline-block;padding:2px 8px;border-radius:999px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:600}
.tbc-consult-badge.is-off{background:#fef2f2;color:#b91c1c}
.tbc-consult-status{min-height:20px;margin-top:8px;font-size:13px;color:#2563eb}
.tbc-consult-status.is-error{color:#dc2626}
.tbc-consult-empty{padding:48px 20px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc}
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc">
    <p>입학안내 <strong>상담신청</strong> 페이지에 표시되는 관별 구글폼 링크를 관리합니다.<br>
    분원은 <strong>분원 관리</strong>에서, 구글폼 링크는 여기서 등록합니다. ▲▼ 으로 같은 관 내 순서를 바꿀 수 있습니다.</p>
</div>

<div class="tbc-consult-toolbar">
    <div></div>
    <form class="tbc-consult-search" method="get" action="./tbc_consults.php">
        <select name="ac_id">
            <option value="0">전체 관</option>
            <?php foreach ($academies as $academy) { ?>
            <option value="<?php echo (int) $academy['ac_id']; ?>"<?php echo $ac_id === (int) $academy['ac_id'] ? ' selected' : ''; ?>>
                <?php echo htmlspecialchars($academy['ac_name'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
            <?php } ?>
        </select>
        <input type="text" name="q" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" class="frm_input" placeholder="제목·URL 검색">
        <input type="submit" value="검색" class="btn btn_02">
        <a href="./tbc_consult_form.php<?php echo $ac_id ? '?ac_id=' . $ac_id : ''; ?>" class="btn btn_01">+ 구글폼 추가</a>
    </form>
</div>

<p id="tbc-consult-status" class="tbc-consult-status" aria-live="polite"></p>

<?php if (!$forms) { ?>
<div class="tbc-consult-empty">등록된 구글폼 링크가 없습니다.</div>
<?php } else { ?>
<div class="tbl_head01 tbl_wrap">
<table class="tbc-consult-table">
<thead>
<tr>
    <th scope="col" style="width:56px;">순서</th>
    <th scope="col" style="width:140px;">관</th>
    <th scope="col">제목 / URL</th>
    <th scope="col" style="width:72px;">노출</th>
    <th scope="col" style="width:140px;">관리</th>
</tr>
</thead>
<tbody id="tbc-consult-list">
<?php
$prev_ac = 0;
$order_in_ac = 0;
foreach ($forms as $form) {
    if ($prev_ac !== (int) $form['ac_id']) {
        $prev_ac = (int) $form['ac_id'];
        $order_in_ac = 0;
    }
    $order_in_ac++;
?>
<tr data-cf-id="<?php echo (int) $form['cf_id']; ?>" data-ac-id="<?php echo (int) $form['ac_id']; ?>">
    <td>
        <div class="tbc-consult-order">
            <span class="tbc-consult-order-num"><?php echo $order_in_ac; ?></span>
            <button type="button" class="tbc-consult-up" title="위로"<?php echo ($keyword || $ac_id) ? ' disabled' : ''; ?>>▲</button>
            <button type="button" class="tbc-consult-down" title="아래로"<?php echo ($keyword || $ac_id) ? ' disabled' : ''; ?>>▼</button>
        </div>
    </td>
    <td><?php echo htmlspecialchars($form['ac_name'], ENT_QUOTES, 'UTF-8'); ?></td>
    <td>
        <strong><?php echo htmlspecialchars($form['cf_title'], ENT_QUOTES, 'UTF-8'); ?></strong>
        <div class="tbc-consult-url"><?php echo htmlspecialchars($form['cf_url'], ENT_QUOTES, 'UTF-8'); ?></div>
    </td>
    <td>
        <span class="tbc-consult-badge<?php echo $form['cf_use'] ? '' : ' is-off'; ?>">
            <?php echo $form['cf_use'] ? '노출' : '숨김'; ?>
        </span>
    </td>
    <td>
        <a href="./tbc_consult_form.php?cf_id=<?php echo (int) $form['cf_id']; ?>" class="btn btn_03">수정</a>
        <button type="button" class="btn btn_02 tbc-consult-delete">삭제</button>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>

<script>
(function () {
    var token = <?php echo json_encode($admin_token); ?>;
    var statusEl = document.getElementById('tbc-consult-status');

    function setStatus(msg, isError) {
        if (!statusEl) return;
        statusEl.textContent = msg || '';
        statusEl.classList.toggle('is-error', !!isError);
    }

    function post(action, data, done) {
        var body = new FormData();
        body.append('token', token);
        body.append('action', action);
        Object.keys(data).forEach(function (key) {
            body.append(key, data[key]);
        });
        fetch('./tbc_consult_ajax.php', { method: 'POST', body: body, credentials: 'same-origin' })
            .then(function (res) { return res.json(); })
            .then(done)
            .catch(function () { setStatus('요청 처리 중 오류가 발생했습니다.', true); });
    }

    document.querySelectorAll('.tbc-consult-up, .tbc-consult-down').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var row = btn.closest('tr');
            if (!row) return;
            post('move_consult', {
                cf_id: row.getAttribute('data-cf-id'),
                direction: btn.classList.contains('tbc-consult-up') ? 'up' : 'down'
            }, function (res) {
                if (res.ok) {
                    window.location.reload();
                    return;
                }
                setStatus(res.message || '순서 변경에 실패했습니다.', true);
            });
        });
    });

    document.querySelectorAll('.tbc-consult-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('이 구글폼 링크를 삭제할까요?')) return;
            var row = btn.closest('tr');
            if (!row) return;
            post('delete_consult', { cf_id: row.getAttribute('data-cf-id') }, function (res) {
                if (res.ok) {
                    window.location.reload();
                    return;
                }
                setStatus(res.message || '삭제에 실패했습니다.', true);
            });
        });
    });
})();
</script>

<?php include_once('./admin.tail.php'); ?>
