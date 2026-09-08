<?php
if (!defined('_GNUBOARD_')) exit;

$tbc_band_links = tbc_band_get_public_list();
$tbc_band_single_url = count($tbc_band_links) === 1 ? $tbc_band_links[0]['bd_url'] : '';
?>
<?php if (count($tbc_band_links) > 1) { ?>
<div id="tbcBandModal" class="tbc-band-modal" aria-hidden="true">
    <div class="tbc-band-modal__backdrop js-tbc-band-close"></div>
    <div class="tbc-band-modal__panel" role="dialog" aria-modal="true" aria-labelledby="tbcBandModalTitle">
        <div class="tbc-band-modal__head">
            <h2 id="tbcBandModalTitle">BAND 선택</h2>
            <button type="button" class="tbc-band-modal__close js-tbc-band-close" aria-label="닫기">&times;</button>
        </div>
        <ul class="tbc-band-modal__list">
            <?php foreach ($tbc_band_links as $band) { ?>
            <li>
                <a href="<?php echo htmlspecialchars($band['bd_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                    <span class="tbc-band-modal__label"><?php echo htmlspecialchars($band['bd_title'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="tbc-band-modal__arrow">→</span>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
<script>
(function() {
    var modal = document.getElementById('tbcBandModal');
    if (!modal) return;

    function openModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('tbc-band-modal-open');
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('tbc-band-modal-open');
    }

    document.addEventListener('click', function(event) {
        var trigger = event.target.closest('.js-tbc-band-open');
        if (trigger) {
            event.preventDefault();
            openModal();
        }
        if (event.target.closest('.js-tbc-band-close')) {
            event.preventDefault();
            closeModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });
})();
</script>
<?php } ?>
