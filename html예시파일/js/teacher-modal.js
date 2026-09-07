(function () {
  'use strict';

  var DEFAULT_IMG = 'img/sub/teacher_pr_sample.jpg';

  function closestEl(el, selector) {
    while (el && el.nodeType === 1) {
      if (typeof el.matches === 'function' && el.matches(selector)) return el;
      if (typeof el.msMatchesSelector === 'function' && el.msMatchesSelector(selector)) return el;
      el = el.parentElement || el.parentNode;
      if (el && el.nodeType !== 1) el = el.parentElement;
    }
    return null;
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
      '      <img src="" alt="">' +
      '    </div>' +
      '  </div>' +
      '</div>';
    document.body.appendChild(modal);
    return modal;
  }

  function openModal(src, title, alt) {
    var modal = ensureModal();
    var img = modal.querySelector('.teacher_pr_modal__media img');
    var titleEl = modal.querySelector('#teacherPrModalTitle');
    titleEl.textContent = title || '강사 자료';
    img.alt = alt || title || '강사 자료';
    img.onload = function () {
      modal.classList.add('is-ready');
    };
    img.onerror = function () {
      img.alt = '이미지를 불러오지 못했습니다.';
      modal.classList.add('is-ready');
    };
    modal.classList.remove('is-ready');
    img.src = src || DEFAULT_IMG;
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
        trigger.getAttribute('data-modal-img') || DEFAULT_IMG,
        trigger.getAttribute('data-modal-title') || '강사 자료',
        trigger.getAttribute('data-modal-alt') || ''
      );
      return;
    }

    if (closestEl(e.target, '[data-close="1"]')) {
      closeModal();
    }
  }

  document.addEventListener('click', onDocClick, true);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' || e.keyCode === 27) closeModal();
  });
})();
