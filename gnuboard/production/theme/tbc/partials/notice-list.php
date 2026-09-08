<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_notice_get_list')) {
    include_once(G5_THEME_PATH . '/tbc.notice.lib.php');
}

tbc_notice_ensure_tables();
tbc_notice_seed_defaults();

$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$per_page = 10;
$sfl = isset($_GET['sfl']) ? preg_replace('/[^a-z_]/', '', $_GET['sfl']) : '';
$stx = isset($_GET['stx']) ? trim($_GET['stx']) : '';

$filters = array(
    'page' => $page,
    'per_page' => $per_page,
    'sfl' => $sfl,
    'stx' => $stx,
);

$total = tbc_notice_count($filters, true);
$list = tbc_notice_get_list($filters, true);
$paging = tbc_notice_paging($total, $page, $per_page, array('sfl' => $sfl, 'stx' => $stx));
$start_no = $total - (($page - 1) * $per_page);
?>
<div id="board1001">
    <div class="list_top">
        <fieldset id="sh_bo_sch">
            <form name="fsearch" method="get" action="<?php echo tbc_notice_list_url(); ?>">
                <input type="hidden" name="p" value="notice">
                <select name="sfl" id="sfl">
                    <option value="subject"<?php echo $sfl === 'subject' ? ' selected' : ''; ?>>제목</option>
                    <option value="content"<?php echo $sfl === 'content' ? ' selected' : ''; ?>>내용</option>
                    <option value="subject_content"<?php echo ($sfl === '' || $sfl === 'subject_content') ? ' selected' : ''; ?>>제목+내용</option>
                    <option value="author"<?php echo $sfl === 'author' ? ' selected' : ''; ?>>글쓴이</option>
                </select>
                <input type="text" name="stx" value="<?php echo htmlspecialchars($stx, ENT_QUOTES, 'UTF-8'); ?>" id="stx" class="sch_input" size="25" maxlength="50" placeholder="검색어">
                <button type="submit" class="sch_submit" aria-label="검색">검색</button>
            </form>
        </fieldset>
    </div>

    <div id="sh_list_tbl" class="sh_tbl_common">
        <table cellpadding="0" cellspacing="0">
            <caption class="sound_only">공지사항 목록</caption>
            <thead>
                <tr>
                    <th class="num" scope="col">No</th>
                    <th scope="col">제목</th>
                    <th class="name" scope="col">작성자</th>
                    <th scope="col">등록일</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$list) { ?>
                <tr>
                    <td colspan="4" class="empty_row">등록된 공지사항이 없습니다.</td>
                </tr>
            <?php } else { ?>
                <?php foreach ($list as $row) {
                    $row_class = $row['nt_is_notice'] ? 'bo_notice' : '';
                    $view_url = tbc_notice_view_url($row['nt_id']);
                ?>
                <tr class="<?php echo $row_class; ?>">
                    <td class="num"><?php echo $row['nt_is_notice'] ? '<i class="fa fa-bell-o" aria-hidden="true"></i>' : (int) $start_no; ?></td>
                    <td class="subject">
                        <div>
                            <a href="<?php echo htmlspecialchars($view_url, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['nt_subject'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php if (tbc_notice_is_new($row['created_at'])) { ?><span class="new">N</span><?php } ?>
                            <?php if ($row['nt_file']) { ?><i class="fa fa-download" aria-hidden="true"></i><?php } ?>
                        </div>
                    </td>
                    <td class="name sv_use"><span class="sv_member"><?php echo htmlspecialchars($row['nt_author'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                    <td class="datetime"><?php echo htmlspecialchars(tbc_notice_format_date($row['created_at']), ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php
                    if (!$row['nt_is_notice']) {
                        $start_no--;
                    }
                } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php echo tbc_notice_render_paging($paging); ?>
</div>
