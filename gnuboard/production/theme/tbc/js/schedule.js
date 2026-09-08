(function () {
  'use strict';

  var DATA = window.TBC_SCHEDULE_DATA || { academyOrder: [], academies: {}, grades: {}, subjects: {}, courses: [] };
  var ACADEMY_ORDER = DATA.academyOrder && DATA.academyOrder.length ? DATA.academyOrder : [];
  var GRADE_ORDER = (DATA.gradeOrder && DATA.gradeOrder.length) ? DATA.gradeOrder : Object.keys(DATA.grades || {});
  var SUBJECT_ORDER = (DATA.subjectOrder && DATA.subjectOrder.length) ? DATA.subjectOrder : Object.keys(DATA.subjects || {});

  function byId(id) {
    return document.getElementById(id);
  }

  function label(map, key) {
    return map && map[key] ? map[key] : key;
  }

  function sortIndex(order, key) {
    var idx = order.indexOf(key);
    return idx === -1 ? 999 : idx;
  }

  function makeChip(type, value, text, active) {
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = active ? 'on' : '';
    btn.setAttribute('data-' + type, value);
    btn.textContent = text;
    return btn;
  }

  function showFilterEmpty(wrap, text) {
    wrap.innerHTML = '';
    var empty = document.createElement('span');
    empty.className = 'sch_filter_empty';
    empty.textContent = text;
    wrap.appendChild(empty);
  }

  function uniqueSorted(values, order) {
    var list = [];
    values.forEach(function (value) {
      if (value && list.indexOf(value) === -1) {
        list.push(value);
      }
    });
    list.sort(function (a, b) {
      return sortIndex(order, a) - sortIndex(order, b);
    });
    return list;
  }

  function getAvailableGrades(academyId) {
    var grades = [];
    DATA.courses.forEach(function (course) {
      if (academyId && course.academy !== academyId) return;
      grades.push(course.grade);
    });
    return uniqueSorted(grades, GRADE_ORDER);
  }

  function getAvailableSubjects(academyId, gradeId) {
    var subjects = [];
    DATA.courses.forEach(function (course) {
      if (academyId && course.academy !== academyId) return;
      if (gradeId && course.grade !== gradeId) return;
      subjects.push(course.subject);
    });
    return uniqueSorted(subjects, SUBJECT_ORDER);
  }

  function renderAcademies(selectedId) {
    var wrap = byId('schAcademyList');
    if (!wrap) return;

    wrap.innerHTML = '';
    wrap.appendChild(makeChip('academy', '', '전체', !selectedId));

    if (!ACADEMY_ORDER.length) {
      showFilterEmpty(wrap, '등록된 관이 없습니다');
      return;
    }

    ACADEMY_ORDER.forEach(function (id) {
      var meta = DATA.academies[id];
      if (!meta) return;
      wrap.appendChild(makeChip('academy', id, meta.name, id === selectedId));
    });
  }

  function renderGrades(academyId, selectedGradeId) {
    var wrap = byId('schGradeList');
    if (!wrap) return null;

    wrap.innerHTML = '';
    var grades = getAvailableGrades(academyId);

    if (!grades.length) {
      showFilterEmpty(wrap, '등록된 학년이 없습니다');
      return null;
    }

    wrap.appendChild(makeChip('grade', '', '전체', !selectedGradeId));

    var activeId = (selectedGradeId && grades.indexOf(selectedGradeId) !== -1) ? selectedGradeId : null;
    grades.forEach(function (gradeId) {
      wrap.appendChild(makeChip('grade', gradeId, label(DATA.grades, gradeId), gradeId === activeId));
    });

    return activeId;
  }

  function renderSubjects(academyId, gradeId, selectedSubjectId) {
    var wrap = byId('schSubjectList');
    if (!wrap) return null;

    wrap.innerHTML = '';
    var subjects = getAvailableSubjects(academyId, gradeId);

    if (!subjects.length) {
      showFilterEmpty(wrap, '등록된 과목이 없습니다');
      return null;
    }

    wrap.appendChild(makeChip('subject', '', '전체', !selectedSubjectId));

    var activeId = (selectedSubjectId && subjects.indexOf(selectedSubjectId) !== -1) ? selectedSubjectId : null;
    subjects.forEach(function (subjectId) {
      wrap.appendChild(makeChip('subject', subjectId, label(DATA.subjects, subjectId), subjectId === activeId));
    });

    return activeId;
  }

  function filterCourses(academyId, gradeId, subjectId) {
    return DATA.courses.filter(function (course) {
      if (academyId && course.academy !== academyId) return false;
      if (gradeId && course.grade !== gradeId) return false;
      if (subjectId && course.subject !== subjectId) return false;
      return true;
    });
  }

  function sortCourses(courses) {
    return courses.slice().sort(function (a, b) {
      var gradeDiff = sortIndex(GRADE_ORDER, a.grade) - sortIndex(GRADE_ORDER, b.grade);
      if (gradeDiff !== 0) return gradeDiff;

      var subjectDiff = sortIndex(SUBJECT_ORDER, a.subject) - sortIndex(SUBJECT_ORDER, b.subject);
      if (subjectDiff !== 0) return subjectDiff;

      var academyDiff = sortIndex(ACADEMY_ORDER, a.academy) - sortIndex(ACADEMY_ORDER, b.academy);
      if (academyDiff !== 0) return academyDiff;

      var orderDiff = (a.order || 0) - (b.order || 0);
      if (orderDiff !== 0) return orderDiff;

      return String(a.name || '').localeCompare(String(b.name || ''), 'ko');
    });
  }

  function escapeHtml(text) {
    return String(text || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function renderScheduleText(slots) {
    if (!slots || !slots.length) return '—';
    return slots.map(function (slot) { return slot.label; }).join('<br>');
  }

  function renderResults(academyId, gradeId, subjectId) {
    var tableWrap = byId('schTableWrap');
    var tableBody = byId('schTableBody');
    var empty = byId('schEmpty');
    if (!tableWrap || !tableBody || !empty) return;

    var courses = sortCourses(filterCourses(academyId, gradeId, subjectId));

    tableBody.innerHTML = '';

    if (!courses.length) {
      tableWrap.style.display = 'none';
      empty.style.display = '';
      return;
    }

    empty.style.display = 'none';
    tableWrap.style.display = '';

    courses.forEach(function (course) {
      var tr = document.createElement('tr');
      tr.innerHTML =
        '<td class="sch_col_grade">' + escapeHtml(label(DATA.grades, course.grade)) + '</td>' +
        '<td class="sch_col_subject">' + escapeHtml(label(DATA.subjects, course.subject)) + '</td>' +
        '<td class="sch_col_name"><strong>' + escapeHtml(course.name) + '</strong></td>' +
        '<td class="sch_col_place">' + escapeHtml((DATA.academies[course.academy] && DATA.academies[course.academy].name) || course.academy || '—') + '</td>' +
        '<td class="sch_col_teacher">' + escapeHtml(course.teacher || '—') + '</td>' +
        '<td class="sch_col_time">' + renderScheduleText(course.slots) + '</td>' +
        '<td class="sch_col_fee">' + escapeHtml(course.fee || '—') + '</td>' +
        '<td class="sch_col_intro">' + (course.introImage
          ? '<button type="button" class="sch_intro_btn" data-image="' + escapeHtml(course.introImage) + '" data-title="' + escapeHtml(course.name) + '">보기</button>'
          : '<span class="sch_intro_none">—</span>') + '</td>';
      tableBody.appendChild(tr);
    });
  }

  function setState(academyId, gradeId, subjectId) {
    var root = byId('schedule1001');
    if (!root) return;
    root.setAttribute('data-academy', academyId || '');
    root.setAttribute('data-grade', gradeId || '');
    root.setAttribute('data-subject', subjectId || '');
  }

  function openIntroModal(image, title) {
    var modal = byId('schIntroModal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'schIntroModal';
      modal.className = 'sch_modal';
      modal.innerHTML =
        '<div class="sch_modal_backdrop" data-close="1"></div>' +
        '<div class="sch_modal_panel" role="dialog" aria-modal="true">' +
          '<div class="sch_modal_head"><strong id="schIntroModalTitle"></strong><button type="button" class="sch_modal_close" data-close="1" aria-label="닫기">×</button></div>' +
          '<div class="sch_modal_body"><img id="schIntroModalImage" alt=""></div>' +
        '</div>';
      document.body.appendChild(modal);
      modal.addEventListener('click', function (e) {
        if (e.target.getAttribute('data-close') === '1') {
          modal.classList.remove('is-open');
          document.body.classList.remove('sch-modal-open');
        }
      });
    }

    byId('schIntroModalTitle').textContent = title || '강좌 소개';
    byId('schIntroModalImage').src = image;
    byId('schIntroModalImage').alt = title || '강좌 소개';
    modal.classList.add('is-open');
    document.body.classList.add('sch-modal-open');
  }

  function refresh(academyId, gradeId, subjectId) {
    renderAcademies(academyId);
    gradeId = renderGrades(academyId, gradeId);
    subjectId = renderSubjects(academyId, gradeId, subjectId);
    setState(academyId, gradeId, subjectId);
    renderResults(academyId, gradeId, subjectId);
  }

  function init() {
    var root = byId('schedule1001');
    if (!root) return;

    refresh('', '', '');

    root.addEventListener('click', function (e) {
      var introBtn = e.target.closest('.sch_intro_btn');
      if (introBtn) {
        openIntroModal(introBtn.getAttribute('data-image'), introBtn.getAttribute('data-title'));
        return;
      }

      var academyBtn = e.target.closest('button[data-academy]');
      if (academyBtn) {
        refresh(academyBtn.getAttribute('data-academy'), '', '');
        return;
      }

      var gradeBtn = e.target.closest('button[data-grade]');
      if (gradeBtn) {
        refresh(root.getAttribute('data-academy') || '', gradeBtn.getAttribute('data-grade'), '');
        return;
      }

      var subjectBtn = e.target.closest('button[data-subject]');
      if (subjectBtn) {
        refresh(
          root.getAttribute('data-academy') || '',
          root.getAttribute('data-grade') || '',
          subjectBtn.getAttribute('data-subject')
        );
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
