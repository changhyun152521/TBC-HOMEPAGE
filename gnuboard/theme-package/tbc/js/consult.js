(function () {
    var root = document.getElementById('consult_page');
    if (!root) return;

    var buttons = root.querySelectorAll('.consult_academy_btn');
    var panels = root.querySelectorAll('.consult_panel');

    function activate(acId) {
        acId = String(acId);
        buttons.forEach(function (btn) {
            var active = btn.getAttribute('data-ac-id') === acId;
            btn.classList.toggle('is-active', active);
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
        panels.forEach(function (panel) {
            var active = panel.getAttribute('data-ac-id') === acId;
            panel.classList.toggle('is-active', active);
            if (active) {
                panel.removeAttribute('hidden');
            } else {
                panel.setAttribute('hidden', 'hidden');
            }
        });
        if (window.feather) {
            feather.replace();
        }
    }

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activate(btn.getAttribute('data-ac-id'));
            var url = new URL(window.location.href);
            url.searchParams.set('ac_id', btn.getAttribute('data-ac-id'));
            window.history.replaceState({}, '', url.toString());
        });
    });
})();
