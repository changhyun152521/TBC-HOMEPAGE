<?php
$sub_menu = '950370';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'r');

include_once(G5_THEME_PATH . '/tbc.edu.lib.php');
tbc_edu_ensure_tables();
tbc_edu_seed_defaults();

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$per_page = 15;

$filters = array(
    'page' => $page,
    'per_page' => $per_page,
    'q' => $keyword,
);

$total = tbc_edu_count($filters, false);
$list = tbc_edu_get_list($filters, false);
$admin_token = get_admin_token();

$g5['title'] = '교육정보 관리';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');
?>

<style>
.tbc-edu-toolbar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin:16px 0 20px}
.tbc-edu-search{display:flex;gap:8px;align-items:center}
.tbc-edu-search input[type="text"]{width:240px}
.tbc-edu-table{width:100%;border-collapse:collapse;background:#fff}
.tbc-edu-table th,.tbc-edu-table td{padding:12px 10px;border-bottom:1px solid #e2e8f0;vertical-align:middle;font-size:13px}
.tbc-edu-table th{background:#f8fafc;color:#475569;text-align:left}
.tbc-edu-thumb{width:72px;height:54px;border-radius:6px;object-fit:cover;border:1px solid #e2e8f0;background:#f1f5f9}
.tbc-edu-badge{display:inline-block;padding:2px 8px;border-radius:4px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:600}
.tbc-edu-badge.is-off{background:#fef2f2;color:#b91c1c}
.tbc-edu-meta{color:#64748b;font-size:12px;margin-top:4px}
.tbc-edu-empty{padding:48px 20px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc}
.tbc-edu-status{min-height:20px;margin-top:8px;font-size:13px;color:#2563eb}
.tbc-edu-status.is-error{color:#dc2626}
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc">
    <p>교육정보 갤러리·밴드형 게시글을 작성합니다. 목록 썸네일과 요약 정보를 입력하고, 본문에는 사진을 자유롭게 삽입할 수 있습니다.</p>
</div>

<div class="tbc-edu-toolbar">
    <form class="tbc-edu-search" method="get" action="./tbc_edu.php">
        <input type="text" name="q" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" class="frm_input" placeholder="제목·내용·작성자 검색">
        <input type="submit" value="검색" class="btn btn_02">
    </form>
    <a href="./tbc_edu_form.php" class="btn btn_03">글 작성</a>
</div>

<?php if (!$list) { ?>
<div class="tbc-edu-empty">등록된 교육정보가 없습니다. <strong>글 작성</strong> 버튼으로 추가해 주세요.</div>
<?php } else { ?>
<div class="tbl_head01 tbl_wrap">
<table class="tbc-edu-table">
<thead>
<tr>
    <th scope="col" style="width:70px;">번호</th>
    <th scope="col" style="width:90px;">썸네일</th>
    <th scope="col">제목</th>
    <th scope="col" style="width:100px;">작성자</th>
    <th scope="col" style="width:110px;">등록일</th>
    <th scope="col" style="width:80px;">조회</th>
    <th scope="col" style="width:90px;">상태</th>
    <th scope="col" style="width:120px;">관리</th>
</tr>
</thead>
<tbody>
<?php foreach ($list as $row) {
    $thumb_url = tbc_edu_resolve_thumb($row);
?>
<tr>
    <td><?php echo (int) $row['ed_id']; ?></td>
    <td>
        <?php if ($thumb_url) { ?>
        <img src="<?php echo htmlspecialchars($thumb_url, ENT_QUOTES, 'UTF-8'); ?>" alt="" class="tbc-edu-thumb">
        <?php } else { ?>
        <span class="tbc-edu-meta">—</span>
        <?php } ?>
    </td>
    <td>
        <strong><?php echo htmlspecialchars($row['ed_subject'], ENT_QUOTES, 'UTF-8'); ?></strong>
        <div class="tbc-edu-meta"><?php echo htmlspecialchars(tbc_edu_excerpt($row['ed_content'], 60), ENT_QUOTES, 'UTF-8'); ?></div>
    </td>
    <td><?php echo htmlspecialchars($row['ed_author'], ENT_QUOTES, 'UTF-8'); ?></td>
    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
    <td><?php echo number_format((int) $row['ed_hit']); ?></td>
    <td><span class="tbc-edu-badge<?php echo $row['ed_use'] ? '' : ' is-off'; ?>"><?php echo $row['ed_use'] ? '노출' : '숨김'; ?></span></td>
    <td>
        <a href="./tbc_edu_form.php?ed_id=<?php echo (int) $row['ed_id']; ?>" class="btn_frmline">수정</a>
        <button type="button" class="btn_frmline js-edu-delete" data-id="<?php echo (int) $row['ed_id']; ?>">삭제</button>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>

<div class="tbc-edu-status" id="tbcEduStatus"></div>

<script>
(function () {
    var token = <?php echo json_encode($admin_token); ?>;
    var statusEl = document.getElementById('tbcEduStatus');

    function setStatus(msg, isError) {
        if (!statusEl) return;
        statusEl.textContent = msg || '';
        statusEl.className = 'tbc-edu-status' + (isError ? ' is-error' : '');
    }

    document.querySelectorAll('.js-edu-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('이 교육정보 글을 삭제할까요?')) return;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', './tbc_edu_ajax.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                var res = {};
                try { res = JSON.parse(xhr.responseText); } catch (e) {}
                if (res.ok) {
                    location.reload();
                    return;
                }
                setStatus(res.message || '삭제에 실패했습니다.', true);
            };
            xhr.send('token=' + encodeURIComponent(token) + '&action=delete&ed_id=' + encodeURIComponent(btn.getAttribute('data-id')));
        });
    });
})();
</script>

<?php include_once('./admin.tail.php'); ?>
