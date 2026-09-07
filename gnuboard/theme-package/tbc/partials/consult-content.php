<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_consult_grouped_by_academy')) {
    include_once(G5_THEME_PATH . '/tbc.consult.lib.php');
}

tbc_academy_ensure_tables();
tbc_consult_ensure_tables();
tbc_consult_seed_defaults();

$groups = tbc_consult_grouped_by_academy(true);
$selected_ac_id = isset($_GET['ac_id']) ? (int) $_GET['ac_id'] : 0;

if (!$selected_ac_id && $groups) {
    $selected_ac_id = (int) $groups[0]['academy']['ac_id'];
}

$has_active = false;
foreach ($groups as $group) {
    if ((int) $group['academy']['ac_id'] === $selected_ac_id) {
        $has_active = true;
        break;
    }
}
if (!$has_active && $groups) {
    $selected_ac_id = (int) $groups[0]['academy']['ac_id'];
}
?>
<div id="consult_page">
    <div class="consult_heading">
        <h2>상담 신청</h2>
        <p>관심 있는 관을 선택하시면 전화·위치와 해당 관의 구글폼 상담 신청 링크를 확인할 수 있습니다.</p>
    </div>

    <?php if (!$groups) { ?>
    <div class="consult_empty">등록된 분원 정보가 없습니다. 관리자 페이지에서 분원을 먼저 등록해 주세요.</div>
    <?php } else { ?>
    <div class="consult_layout">
        <aside class="consult_academy_nav" aria-label="관 선택">
            <h3>관 선택</h3>
            <ul>
                <?php foreach ($groups as $group) {
                    $academy = $group['academy'];
                    $is_active = ((int) $academy['ac_id'] === $selected_ac_id);
                ?>
                <li>
                    <button type="button"
                        class="consult_academy_btn<?php echo $is_active ? ' is-active' : ''; ?>"
                        data-ac-id="<?php echo (int) $academy['ac_id']; ?>"
                        aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>">
                        <strong><?php echo htmlspecialchars($academy['ac_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        <span><?php echo htmlspecialchars($academy['type_name'] . ' · ' . $academy['ac_region'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </button>
                </li>
                <?php } ?>
            </ul>
        </aside>

        <div class="consult_panels">
            <?php foreach ($groups as $group) {
                $academy = $group['academy'];
                $forms = $group['forms'];
                $phone = tbc_consult_phone($academy);
                $map_url = trim($academy['ac_map_url']);
                $is_active = ((int) $academy['ac_id'] === $selected_ac_id);
            ?>
            <section class="consult_panel<?php echo $is_active ? ' is-active' : ''; ?>"
                id="consult-panel-<?php echo (int) $academy['ac_id']; ?>"
                data-ac-id="<?php echo (int) $academy['ac_id']; ?>"
                <?php echo $is_active ? '' : 'hidden'; ?>>
                <div class="consult_info">
                    <h3><?php echo htmlspecialchars($academy['ac_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <dl>
                        <dt>구분</dt>
                        <dd><?php echo htmlspecialchars($academy['type_name'], ENT_QUOTES, 'UTF-8'); ?></dd>
                        <dt>지역</dt>
                        <dd><?php echo htmlspecialchars($academy['ac_region'], ENT_QUOTES, 'UTF-8'); ?></dd>
                        <dt>주소</dt>
                        <dd><?php echo htmlspecialchars($academy['ac_address'], ENT_QUOTES, 'UTF-8'); ?></dd>
                        <dt>전화</dt>
                        <dd><?php echo $phone ? htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') : '문의 중'; ?></dd>
                        <?php if ($map_url) { ?>
                        <dt>위치</dt>
                        <dd>
                            <a href="<?php echo htmlspecialchars($map_url, ENT_QUOTES, 'UTF-8'); ?>" class="map_link" target="_blank" rel="noopener noreferrer">
                                지도에서 보기 <i data-feather="map-pin"></i>
                            </a>
                        </dd>
                        <?php } ?>
                    </dl>
                </div>

                <div class="consult_form_section">
                    <h4>구글폼 상담 신청</h4>
                    <?php if ($forms) { ?>
                    <ul class="consult_form_list">
                        <?php foreach ($forms as $form) { ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($form['cf_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                                <div>
                                    <div class="form_title"><?php echo htmlspecialchars($form['cf_title'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="form_meta">새 창에서 구글폼이 열립니다</div>
                                </div>
                                <span class="form_arrow" aria-hidden="true"><i data-feather="arrow-up-right"></i></span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                    <div class="consult_empty">
                        현재 이 관에 등록된 상담 신청 폼이 없습니다.<br>
                        <?php if ($phone) { ?>전화 <?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>로 문의해 주세요.<?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </section>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
