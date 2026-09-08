(function () {
  'use strict';

  var DEFAULT_IMG = 'img/sub/teacher_pr_sample.jpg';

  function resolveImageSrc(src) {
    if (!src) {
      return getDefaultImg();
    }
    if (/^https?:\/\//i.test(src) || src.charAt(0) === '/') {
      return src;
    }
    if (src.indexOf('img/') === 0 && window.TBC_THEME_URL) {
      return window.TBC_THEME_URL + '/' + src;
    }
    return src;
  }

  function getDefaultImg() {
    if (window.TBC_THEME_URL) {
      return window.TBC_THEME_URL + '/' + DEFAULT_IMG;
    }
    return DEFAULT_IMG;
  }
  var state = { images: [], index: 0, title: '', alt: '' };

  function closestEl(el, selector) {
    while (el && el.nodeType === 1) {
      if (typeof el.matches === 'function' && el.matches(selector)) return el;
      if (typeof el.msMatchesSelector === 'function' && el.msMatchesSelector(selector)) return el;
      el = el.parentElement || el.parentNode;
      if (el && el.nodeType !== 1) el = el.parentElement;
    }
    return null;
  }

  function parseImages(trigger) {
    var raw = trigger.getAttribute('data-modal-images');
    if (raw) {
      try {
        var parsed = JSON.parse(raw);
        if (Array.isArray(parsed) && parsed.length) {
          return parsed;
        }
      } catch (e) {}
    }

    var single = trigger.getAttribute('data-modal-img');
    if (single) {
      return [resolveImageSrc(single)];
    }

    return [getDefaultImg()];
  }

  function ensureModal() {
    var modal = document.getElementById('teacherPrModal');
    if (modal) return modal;

    modal = document.createElement('div');
    modal.id = 'teacherPrModal';
    modal.className = 'teacher_pr_modal';
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML =
      '<div class="teacher_pr_modal__dim" data-close="1"></div>' +
      '<div class="teacher_pr_modal__panel" role="dialog" aria-modal="true" aria-labelledby="teacherPrModalTitle">' +
      '  <div class="teacher_pr_modal__head">' +
      '    <p class="teacher_pr_modal__eyebrow">더브레인코어 강사진</p>' +
      '    <h3 class="teacher_pr_modal__title" id="teacherPrModalTitle">수업 특징</h3>' +
      '    <button type="button" class="teacher_pr_modal__close" data-close="1" aria-label="닫기">' +
      '      <span aria-hidden="true">&times;</span>' +
      '    </button>' +
      '  </div>' +
      '  <div class="teacher_pr_modal__body">' +
      '    <div class="teacher_pr_modal__media">' +
      '      <button type="button" class="teacher_pr_modal__nav teacher_pr_modal__nav--prev" data-nav="prev" aria-label="이전 이미지">&lsaquo;</button>' +
      '      <img src="" alt="">' +
      '      <button type="button" class="teacher_pr_modal__nav teacher_pr_modal__nav--next" data-nav="next" aria-label="다음 이미지">&rsaquo;</button>' +
      '    </div>' +
      '    <p class="teacher_pr_modal__counter" aria-live="polite"></p>' +
      '  </div>' +
      '</div>';
    document.body.appendChild(modal);
    return modal;
  }

  function updateCounter(modal) {
    var counter = modal.querySelector('.teacher_pr_modal__counter');
    if (!counter) return;

    if (state.images.length <= 1) {
      counter.textContent = '';
      counter.style.display = 'none';
      return;
    }

    counter.style.display = '';
    counter.textContent = (state.index + 1) + ' / ' + state.images.length;
  }

  function updateNav(modal) {
    var prev = modal.querySelector('[data-nav="prev"]');
    var next = modal.querySelector('[data-nav="next"]');
    var show = state.images.length > 1;

    if (prev) prev.style.display = show ? '' : 'none';
    if (next) next.style.display = show ? '' : 'none';
  }

  function showImage(modal, index) {
    if (!state.images.length) return;

    if (index < 0) index = state.images.length - 1;
    if (index >= state.images.length) index = 0;
    state.index = index;

    var img = modal.querySelector('.teacher_pr_modal__media img');
    var titleEl = modal.querySelector('#teacherPrModalTitle');

    titleEl.textContent = state.title || '강사 자료';
    img.alt = state.alt || state.title || '강사 자료';
    modal.classList.remove('is-ready');
    img.onload = function () {
      modal.classList.add('is-ready');
    };
    img.onerror = function () {
      img.alt = '이미지를 불러오지 못했습니다.';
      modal.classList.add('is-ready');
    };
    img.src = resolveImageSrc(state.images[state.index]) || getDefaultImg();
    updateCounter(modal);
    updateNav(modal);
  }

  function openModal(images, title, alt) {
    var modal = ensureModal();
    state.images = images && images.length ? images : [getDefaultImg()];
    state.index = 0;
    state.title = title || '강사 자료';
    state.alt = alt || title || '강사 자료';

    showImage(modal, 0);
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('teacher_pr_modal_open');
  }

  function closeModal() {
    var modal = document.getElementById('teacherPrModal');
    if (!modal) return;
    modal.classList.remove('is-open', 'is-ready');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('teacher_pr_modal_open');
  }

  function onDocClick(e) {
    var trigger = closestEl(e.target, 'a.js-teacher-modal');
    if (trigger) {
      e.preventDefault();
      e.stopPropagation();
      openModal(
        parseImages(trigger),
        trigger.getAttribute('data-modal-title') || '강사 자료',
        trigger.getAttribute('data-modal-alt') || ''
      );
      return;
    }

    var navBtn = closestEl(e.target, '[data-nav]');
    if (navBtn) {
      var modal = document.getElementById('teacherPrModal');
      if (!modal || !modal.classList.contains('is-open')) return;
      e.preventDefault();
      var dir = navBtn.getAttribute('data-nav');
      showImage(modal, dir === 'prev' ? state.index - 1 : state.index + 1);
      return;
    }

    if (closestEl(e.target, '[data-close="1"]')) {
      closeModal();
    }
  }

  document.addEventListener('click', onDocClick, true);

  document.addEventListener('keydown', function (e) {
    var modal = document.getElementById('teacherPrModal');
    if (!modal || !modal.classList.contains('is-open')) {
      if (e.key === 'Escape' || e.keyCode === 27) closeModal();
      return;
    }

    if (e.key === 'Escape' || e.keyCode === 27) {
      closeModal();
      return;
    }

    if (e.key === 'ArrowLeft' || e.keyCode === 37) {
      showImage(modal, state.index - 1);
      return;
    }

    if (e.key === 'ArrowRight' || e.keyCode === 39) {
      showImage(modal, state.index + 1);
    }
  });
})();
