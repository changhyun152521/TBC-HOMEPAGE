<?php
$sub_menu = '950225';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

include_once(G5_THEME_PATH . '/tbc.band.lib.php');
tbc_band_ensure_tables();
tbc_band_seed_defaults();
tbc_band_normalize_orders();

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$bands = tbc_band_get_list($keyword);
$admin_token = get_admin_token();

$g5['title'] = 'BAND 링크 관리';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');
?>

<style>
.tbc-band-toolbar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin:16px 0 20px}
.tbc-band-search{display:flex;gap:8px;align-items:center}
.tbc-band-search input[type="text"]{width:220px}
.tbc-band-table{width:100%;border-collapse:collapse;background:#fff}
.tbc-band-table th,.tbc-band-table td{padding:12px 10px;border-bottom:1px solid #e2e8f0;vertical-align:middle;font-size:13px}
.tbc-band-table th{background:#f8fafc;color:#475569;text-align:left}
.tbc-band-order{display:flex;flex-direction:column;gap:4px;align-items:center}
.tbc-band-order-num{width:28px;height:28px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700}
.tbc-band-order button{width:28px;height:24px;border:1px solid #cbd5e1;border-radius:6px;background:#fff;cursor:pointer}
.tbc-band-order button:disabled{opacity:.35;cursor:not-allowed}
.tbc-band-url{color:#64748b;font-size:12px;word-break:break-all}
.tbc-band-badge{display:inline-block;padding:2px 8px;border-radius:999px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:600}
.tbc-band-badge.is-off{background:#fef2f2;color:#b91c1c}
.tbc-band-status{min-height:20px;margin-top:8px;font-size:13px;color:#2563eb}
.tbc-band-status.is-error{color:#dc2626}
.tbc-band-empty{padding:48px 20px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc}
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc">
    <p>메인·하단 <strong>BAND 바로가기</strong> 버튼에 연결되는 링크를 관리합니다.<br>
    링크가 2개 이상이면 버튼 클릭 시 선택 창이 열립니다. ▲▼ 으로 순서를 바꿀 수 있습니다.</p>
</div>

<div class="tbc-band-toolbar">
    <div></div>
    <form class="tbc-band-search" method="get" action="./tbc_bands.php">
        <input type="text" name="q" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" class="frm_input" placeholder="제목·URL 검색">
        <input type="submit" value="검색" class="btn btn_02">
        <a href="./tbc_band_form.php" class="btn btn_01">+ BAND 추가</a>
    </form>
</div>

<p id="tbc-band-status" class="tbc-band-status" aria-live="polite"></p>

<?php if (!$bands) { ?>
<div class="tbc-band-empty">등록된 BAND 링크가 없습니다.</div>
<?php } else { ?>
<div class="tbl_head01 tbl_wrap">
<table class="tbc-band-table">
<thead>
<tr>
    <th scope="col" style="width:56px;">순서</th>
    <th scope="col">제목 / URL</th>
    <th scope="col" style="width:72px;">노출</th>
    <th scope="col" style="width:140px;">관리</th>
</tr>
</thead>
<tbody id="tbc-band-list">
<?php foreach ($bands as $i => $band) { ?>
<tr data-bd-id="<?php echo (int) $band['bd_id']; ?>">
    <td>
        <div class="tbc-band-order">
            <span class="tbc-band-order-num"><?php echo $i + 1; ?></span>
            <button type="button" class="tbc-band-up" title="위로"<?php echo $keyword ? ' disabled' : ''; ?>>▲</button>
            <button type="button" class="tbc-band-down" title="아래로"<?php echo $keyword ? ' disabled' : ''; ?>>▼</button>
        </div>
    </td>
    <td>
        <strong><?php echo htmlspecialchars($band['bd_title'], ENT_QUOTES, 'UTF-8'); ?></strong>
        <div class="tbc-band-url"><?php echo htmlspecialchars($band['bd_url'], ENT_QUOTES, 'UTF-8'); ?></div>
    </td>
    <td>
        <span class="tbc-band-badge<?php echo $band['bd_use'] ? '' : ' is-off'; ?>">
            <?php echo $band['bd_use'] ? '노출' : '숨김'; ?>
        </span>
    </td>
    <td>
        <a href="./tbc_band_form.php?bd_id=<?php echo (int) $band['bd_id']; ?>" class="btn btn_03">수정</a>
        <button type="button" class="btn btn_02 tbc-band-delete">삭제</button>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>

<script>
(function($) {
    var ajaxUrl = './tbc_band_ajax.php';
    var adminToken = <?php echo json_encode($admin_token); ?>;
    var canReorder = <?php echo $keyword ? 'false' : 'true'; ?>;
    var $list = $('#tbc-band-list');
    var $status = $('#tbc-band-status');

    function setStatus(message, isError) {
        $status.text(message || '').toggleClass('is-error', !!isError);
    }

    function postAction(data, onSuccess, onFail) {
        data.token = adminToken;
        $.ajax({ url: ajaxUrl, method: 'POST', dataType: 'json', data: data })
            .done(function(res) {
                if (!res || !res.ok) { onFail(res && res.message ? res.message : '요청에 실패했습니다.'); return; }
                onSuccess(res);
            }).fail(function() { onFail('서버 통신에 실패했습니다.'); });
    }

    function updateOrderButtons() {
        var rows = $list.find('tr');
        rows.each(function(index) {
            $(this).find('.tbc-band-order-num').text(index + 1);
            if (!canReorder) {
                $(this).find('.tbc-band-up, .tbc-band-down').prop('disabled', true);
                return;
            }
            $(this).find('.tbc-band-up').prop('disabled', index === 0);
            $(this).find('.tbc-band-down').prop('disabled', index === rows.length - 1);
        });
    }

    $list.on('click', '.tbc-band-up, .tbc-band-down', function() {
        if (!canReorder || $(this).prop('disabled')) return;
        var $row = $(this).closest('tr');
        var bdId = $row.data('bd-id');
        var direction = $(this).hasClass('tbc-band-up') ? 'up' : 'down';
        var $target = direction === 'up' ? $row.prev('tr') : $row.next('tr');
        if (!$target.length) return;
        if (direction === 'up') $row.insertBefore($target); else $row.insertAfter($target);
        updateOrderButtons();
        postAction({ action: 'move_band', bd_id: bdId, direction: direction }, function() {
            setStatus('순서가 변경되었습니다.');
        }, function(message) {
            if (direction === 'up') $row.insertAfter($target); else $row.insertBefore($target);
            updateOrderButtons();
            setStatus(message, true);
        });
    });

    $list.on('click', '.tbc-band-delete', function() {
        var $row = $(this).closest('tr');
        if (!window.confirm('이 BAND 링크를 삭제하시겠습니까?')) return;
        postAction({ action: 'delete_band', bd_id: $row.data('bd-id') }, function() {
            $row.remove();
            updateOrderButtons();
            setStatus('삭제되었습니다.');
            if (!$list.find('tr').length) window.location.reload();
        }, function(message) { setStatus(message, true); });
    });

    updateOrderButtons();
})(jQuery);
</script>

<?php include_once('./admin.tail.php'); ?>
