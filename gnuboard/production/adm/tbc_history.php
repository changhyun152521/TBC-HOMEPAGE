<?php
$sub_menu = '950210';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

include_once(G5_THEME_PATH . '/tbc.history.lib.php');
tbc_history_ensure_tables();
tbc_history_seed_defaults();
tbc_history_normalize_orders();

$config = tbc_history_get_config();
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$entries = tbc_history_get_list($keyword);
$admin_token = get_admin_token();

$g5['title'] = '연혁 관리';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');
?>

<style>
.tbc-history-section{margin-bottom:36px}
.tbc-history-section h2{margin:0 0 8px;font-size:16px;font-weight:700;color:#1e293b}
.tbc-history-section p{margin:0 0 14px;color:#64748b;font-size:13px}
.tbc-history-toolbar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:flex-end;margin:16px 0 20px}
.tbc-history-search{display:flex;gap:8px;align-items:center}
.tbc-history-search input[type="text"]{width:220px}
.tbc-history-table{width:100%;border-collapse:collapse;background:#fff}
.tbc-history-table th,.tbc-history-table td{padding:12px 10px;border-bottom:1px solid #e2e8f0;vertical-align:top;font-size:13px}
.tbc-history-table th{background:#f8fafc;color:#475569;text-align:left}
.tbc-history-order{display:flex;flex-direction:column;gap:4px;align-items:center}
.tbc-history-order-num{width:28px;height:28px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700}
.tbc-history-order button{width:28px;height:24px;border:1px solid #cbd5e1;border-radius:6px;background:#fff;cursor:pointer}
.tbc-history-order button:disabled{opacity:.35;cursor:not-allowed}
.tbc-history-year{font-weight:700;color:#0f172a;font-size:15px}
.tbc-history-items{color:#64748b;font-size:12px;margin-top:6px;white-space:pre-line}
.tbc-history-badge{display:inline-block;padding:2px 8px;border-radius:999px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:600}
.tbc-history-badge.is-off{background:#fef2f2;color:#b91c1c}
.tbc-history-status{min-height:20px;margin-top:8px;font-size:13px;color:#2563eb}
.tbc-history-status.is-error{color:#dc2626}
.tbc-history-empty{padding:48px 20px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc}
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc">
    <p>연혁 페이지 상단 문구와 연도별 타임라인을 관리합니다.<br>
    아래 목록에서 ▲▼ 버튼으로 노출 순서를 바꿀 수 있습니다. (맨 위 항목이 강조 색으로 표시됩니다.)</p>
</div>

<div class="tbc-history-section">
    <h2>① 상단 문구</h2>
    <p>연혁 페이지 왼쪽에 표시되는 영문 라벨·제목·설명입니다.</p>

    <form action="./tbc_history_update.php" method="post">
    <input type="hidden" name="token" value="<?php echo $admin_token; ?>">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup><col class="grid_4"><col></colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="hc_label_en">영문 라벨</label></th>
            <td><input type="text" name="hc_label_en" id="hc_label_en" value="<?php echo get_text($config['hc_label_en']); ?>" class="frm_input" style="width:100%;max-width:320px;" placeholder="예: HISTORY"></td>
        </tr>
        <tr>
            <th scope="row"><label for="hc_title_bold">제목 — 굵게</label></th>
            <td><input type="text" name="hc_title_bold" id="hc_title_bold" value="<?php echo get_text($config['hc_title_bold']); ?>" class="frm_input" style="width:100%;max-width:420px;" placeholder="예: 더브레인코어"></td>
        </tr>
        <tr>
            <th scope="row"><label for="hc_title_text">제목 — 나머지</label></th>
            <td><input type="text" name="hc_title_text" id="hc_title_text" value="<?php echo get_text($config['hc_title_text']); ?>" class="frm_input" style="width:100%;max-width:420px;" placeholder="예: 가 걸어온 길"></td>
        </tr>
        <tr>
            <th scope="row"><label for="hc_desc">설명</label></th>
            <td><textarea name="hc_desc" id="hc_desc" rows="4" class="frm_input" style="width:100%;max-width:640px;"><?php echo get_text($config['hc_desc']); ?></textarea></td>
        </tr>
        </tbody>
        </table>
    </div>
    <div class="btn_confirm01 btn_confirm" style="margin-top:12px;">
        <input type="submit" value="상단 문구 저장" class="btn_submit btn">
    </div>
    </form>
</div>

<div class="tbc-history-section">
    <h2>② 연도별 연혁</h2>
    <p>한 줄에 하나의 연혁 내용을 입력합니다. 검색 중에는 순서 변경이 비활성화됩니다.</p>

    <div class="tbc-history-toolbar">
        <form class="tbc-history-search" method="get" action="./tbc_history.php">
            <input type="text" name="q" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" class="frm_input" placeholder="연도·내용 검색">
            <input type="submit" value="검색" class="btn btn_02">
            <a href="./tbc_history_entry_form.php" class="btn btn_01">+ 연혁 추가</a>
        </form>
    </div>

    <p id="tbc-history-status" class="tbc-history-status" aria-live="polite"></p>

    <?php if (!$entries) { ?>
    <div class="tbc-history-empty">
        <?php echo $keyword ? '검색 결과가 없습니다.' : '등록된 연혁이 없습니다. 「연혁 추가」 버튼으로 등록해 주세요.'; ?>
    </div>
    <?php } else { ?>
    <div class="tbl_head01 tbl_wrap">
    <table class="tbc-history-table">
    <thead>
    <tr>
        <th scope="col" style="width:56px;">순서</th>
        <th scope="col" style="width:88px;">연도</th>
        <th scope="col">내용</th>
        <th scope="col" style="width:72px;">노출</th>
        <th scope="col" style="width:140px;">관리</th>
    </tr>
    </thead>
    <tbody id="tbc-history-list">
    <?php foreach ($entries as $i => $entry) { ?>
    <tr data-he-id="<?php echo (int) $entry['he_id']; ?>">
        <td>
            <div class="tbc-history-order">
                <span class="tbc-history-order-num"><?php echo $i + 1; ?></span>
                <button type="button" class="tbc-history-up" title="위로"<?php echo $keyword ? ' disabled' : ''; ?>>▲</button>
                <button type="button" class="tbc-history-down" title="아래로"<?php echo $keyword ? ' disabled' : ''; ?>>▼</button>
            </div>
        </td>
        <td><span class="tbc-history-year"><?php echo htmlspecialchars($entry['he_year'], ENT_QUOTES, 'UTF-8'); ?></span></td>
        <td><div class="tbc-history-items"><?php echo htmlspecialchars(implode("\n", $entry['items']), ENT_QUOTES, 'UTF-8'); ?></div></td>
        <td>
            <span class="tbc-history-badge<?php echo $entry['he_use'] ? '' : ' is-off'; ?>">
                <?php echo $entry['he_use'] ? '노출' : '숨김'; ?>
            </span>
        </td>
        <td>
            <a href="./tbc_history_entry_form.php?he_id=<?php echo (int) $entry['he_id']; ?>" class="btn btn_03">수정</a>
            <button type="button" class="btn btn_02 tbc-history-delete">삭제</button>
        </td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
    </div>
    <?php } ?>
</div>

<script>
(function($) {
    var ajaxUrl = './tbc_history_ajax.php';
    var adminToken = <?php echo json_encode($admin_token); ?>;
    var canReorder = <?php echo $keyword ? 'false' : 'true'; ?>;
    var $list = $('#tbc-history-list');
    var $status = $('#tbc-history-status');

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
        if (!$list.length) return;
        var rows = $list.find('tr');
        rows.each(function(index) {
            $(this).find('.tbc-history-order-num').text(index + 1);
            if (!canReorder) {
                $(this).find('.tbc-history-up, .tbc-history-down').prop('disabled', true);
                return;
            }
            $(this).find('.tbc-history-up').prop('disabled', index === 0);
            $(this).find('.tbc-history-down').prop('disabled', index === rows.length - 1);
        });
    }

    $list.on('click', '.tbc-history-up, .tbc-history-down', function() {
        if (!canReorder || $(this).prop('disabled')) return;
        var $row = $(this).closest('tr');
        var heId = $row.data('he-id');
        var direction = $(this).hasClass('tbc-history-up') ? 'up' : 'down';
        var $target = direction === 'up' ? $row.prev('tr') : $row.next('tr');
        if (!$target.length) return;
        if (direction === 'up') $row.insertBefore($target); else $row.insertAfter($target);
        updateOrderButtons();
        postAction({ action: 'move_entry', he_id: heId, direction: direction }, function() {
            setStatus('순서가 변경되었습니다.');
        }, function(message) {
            if (direction === 'up') $row.insertAfter($target); else $row.insertBefore($target);
            updateOrderButtons();
            setStatus(message, true);
        });
    });

    $list.on('click', '.tbc-history-delete', function() {
        var $row = $(this).closest('tr');
        if (!window.confirm('이 연혁 항목을 삭제하시겠습니까?')) return;
        postAction({ action: 'delete_entry', he_id: $row.data('he-id') }, function() {
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
