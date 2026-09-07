<?php
$sub_menu = '950360';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'r');

include_once(G5_THEME_PATH . '/tbc.notice.lib.php');
tbc_notice_ensure_tables();
tbc_notice_seed_defaults();

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$per_page = 15;

$filters = array(
    'page' => $page,
    'per_page' => $per_page,
    'q' => $keyword,
);

$total = tbc_notice_count($filters, false);
$list = tbc_notice_get_list($filters, false);
$admin_token = get_admin_token();

$g5['title'] = '공지사항 관리';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');
?>

<style>
.tbc-notice-toolbar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin:16px 0 20px}
.tbc-notice-search{display:flex;gap:8px;align-items:center}
.tbc-notice-search input[type="text"]{width:240px}
.tbc-notice-table{width:100%;border-collapse:collapse;background:#fff}
.tbc-notice-table th,.tbc-notice-table td{padding:12px 10px;border-bottom:1px solid #e2e8f0;vertical-align:middle;font-size:13px}
.tbc-notice-table th{background:#f8fafc;color:#475569;text-align:left}
.tbc-notice-badge{display:inline-block;padding:2px 8px;border-radius:4px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:600}
.tbc-notice-badge.is-pin{background:#fef3c7;color:#b45309}
.tbc-notice-badge.is-off{background:#fef2f2;color:#b91c1c}
.tbc-notice-meta{color:#64748b;font-size:12px;margin-top:4px}
.tbc-notice-empty{padding:48px 20px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc}
.tbc-notice-status{min-height:20px;margin-top:8px;font-size:13px;color:#2563eb}
.tbc-notice-status.is-error{color:#dc2626}
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc">
    <p>홈페이지 공지사항 게시글을 작성·수정합니다. 상단 고정 공지는 목록에서 강조 표시됩니다.</p>
</div>

<div class="tbc-notice-toolbar">
    <form class="tbc-notice-search" method="get" action="./tbc_notices.php">
        <input type="text" name="q" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" class="frm_input" placeholder="제목·내용·작성자 검색">
        <input type="submit" value="검색" class="btn btn_02">
    </form>
    <a href="./tbc_notice_form.php" class="btn btn_03">공지 작성</a>
</div>

<?php if (!$list) { ?>
<div class="tbc-notice-empty">등록된 공지사항이 없습니다. <strong>공지 작성</strong> 버튼으로 추가해 주세요.</div>
<?php } else { ?>
<div class="tbl_head01 tbl_wrap">
<table class="tbc-notice-table">
<thead>
<tr>
    <th scope="col" style="width:70px;">번호</th>
    <th scope="col">제목</th>
    <th scope="col" style="width:100px;">작성자</th>
    <th scope="col" style="width:110px;">등록일</th>
    <th scope="col" style="width:80px;">조회</th>
    <th scope="col" style="width:110px;">상태</th>
    <th scope="col" style="width:130px;">관리</th>
</tr>
</thead>
<tbody>
<?php foreach ($list as $row) { ?>
<tr data-id="<?php echo (int) $row['nt_id']; ?>">
    <td><?php echo (int) $row['nt_id']; ?></td>
    <td>
        <strong><?php echo htmlspecialchars($row['nt_subject'], ENT_QUOTES, 'UTF-8'); ?></strong>
        <?php if ($row['nt_file']) { ?><div class="tbc-notice-meta">첨부파일 있음</div><?php } ?>
    </td>
    <td><?php echo htmlspecialchars($row['nt_author'], ENT_QUOTES, 'UTF-8'); ?></td>
    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
    <td><?php echo number_format((int) $row['nt_hit']); ?></td>
    <td>
        <?php if ($row['nt_is_notice']) { ?><span class="tbc-notice-badge is-pin">상단고정</span> <?php } ?>
        <span class="tbc-notice-badge<?php echo $row['nt_use'] ? '' : ' is-off'; ?>"><?php echo $row['nt_use'] ? '노출' : '숨김'; ?></span>
    </td>
    <td>
        <a href="./tbc_notice_form.php?nt_id=<?php echo (int) $row['nt_id']; ?>" class="btn_frmline">수정</a>
        <button type="button" class="btn_frmline js-notice-delete">삭제</button>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>

<div class="tbc-notice-status" id="tbcNoticeStatus"></div>

<script>
(function () {
    var token = <?php echo json_encode($admin_token); ?>;
    var statusEl = document.getElementById('tbcNoticeStatus');

    function setStatus(msg, isError) {
        if (!statusEl) return;
        statusEl.textContent = msg || '';
        statusEl.className = 'tbc-notice-status' + (isError ? ' is-error' : '');
    }

    document.querySelectorAll('.js-notice-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('이 공지사항을 삭제할까요?')) return;
            var row = btn.closest('tr');
            var id = row.getAttribute('data-id');
            var xhr = new XMLHttpRequest();
            xhr.open('POST', './tbc_notice_ajax.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            xhr.onload = function () {
                var res;
                try { res = JSON.parse(xhr.responseText); } catch (e) { setStatus('응답 처리 오류', true); return; }
                if (!res.ok) { setStatus(res.message || '삭제 실패', true); return; }
                row.remove();
                setStatus('삭제되었습니다.');
            };
            xhr.send('token=' + encodeURIComponent(token) + '&action=delete&nt_id=' + encodeURIComponent(id));
        });
    });
})();
</script>

<?php include_once('./admin.tail.php'); ?>
