<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_edu_get_list')) {
    include_once(G5_THEME_PATH . '/tbc.edu.lib.php');
}

tbc_edu_ensure_tables();
tbc_edu_seed_defaults();

$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$per_page = 8;
$sfl = isset($_GET['sfl']) ? preg_replace('/[^a-z_]/', '', $_GET['sfl']) : '';
$stx = isset($_GET['stx']) ? trim($_GET['stx']) : '';

$filters = array(
    'page' => $page,
    'per_page' => $per_page,
    'sfl' => $sfl,
    'stx' => $stx,
);

$total = tbc_edu_count($filters, true);
$list = tbc_edu_get_list($filters, true);
$paging = tbc_edu_paging($total, $page, $per_page, array('sfl' => $sfl, 'stx' => $stx));
$fallback_images = array('img01.jpg', 'img02.jpg', 'img03.jpg', 'img04.jpg');
?>
<div id="board1006">
    <div class="list_top">
        <fieldset id="sh_bo_sch">
            <form name="fsearch" method="get" action="<?php echo tbc_edu_list_url(); ?>">
                <input type="hidden" name="p" value="edu">
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

    <div id="sh_bo_gall">
        <?php if (!$list) { ?>
        <div class="edu_empty">등록된 교육정보가 없습니다.</div>
        <?php } else { ?>
        <ul id="sh_gall_ul" class="gall_row">
            <?php foreach ($list as $idx => $row) {
                $view_url = tbc_edu_view_url($row['ed_id']);
                $thumb_url = tbc_edu_resolve_thumb($row);
                if (!$thumb_url) {
                    $thumb_url = G5_THEME_URL . '/img/sub/' . $fallback_images[$idx % count($fallback_images)];
                }
            ?>
            <li class="gall_li">
                <div class="gall_con">
                    <div class="gall_img">
                        <a href="<?php echo htmlspecialchars($view_url, ENT_QUOTES, 'UTF-8'); ?>">
                            <img src="<?php echo htmlspecialchars($thumb_url, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['ed_subject'], ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($row['ed_subject'], ENT_QUOTES, 'UTF-8'); ?>">
                        </a>
                    </div>
                    <div class="gall_txt">
                        <div class="subject">
                            <a href="<?php echo htmlspecialchars($view_url, ENT_QUOTES, 'UTF-8'); ?>" class="tit"><?php echo htmlspecialchars($row['ed_subject'], ENT_QUOTES, 'UTF-8'); ?></a>
                        </div>
                        <div class="desc">
                            <?php echo tbc_edu_render_list_summary($row); ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>
    </div>

    <?php echo tbc_edu_render_paging($paging); ?>
</div>
