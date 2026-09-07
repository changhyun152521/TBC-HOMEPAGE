(function () {
  'use strict';

  var SCHEDULE_DATA = {
    elementary: {
      name: '초등관',
      group: 'main',
      grades: []
    },
    middle: {
      name: '중등관',
      group: 'main',
      grades: []
    },
    high: {
      name: '고등관',
      group: 'main',
      grades: [
        {
          id: 'g1',
          label: '고1',
          images: [
            { src: 'img/schedule/high1-1.png', alt: '고등관 고1 시간표 1' },
            { src: 'img/schedule/high1-2.png', alt: '고등관 고1 시간표 2' }
          ]
        },
        {
          id: 'g2',
          label: '고2 수학',
          images: [
            { src: 'img/schedule/high2-1.png', alt: '고등관 고2 수학 시간표 1' },
            { src: 'img/schedule/high2-2.png', alt: '고등관 고2 수학 시간표 2' }
          ]
        }
      ]
    },
    science: {
      name: '과학관',
      group: 'main',
      grades: []
    },
    alpha: {
      name: '알파',
      group: 'main',
      grades: []
    },
    fullstory: {
      name: '풀스토리',
      group: 'main',
      grades: []
    },
    noeun: {
      name: '노은관',
      group: 'branch',
      grades: []
    },
    gwanpyeong: {
      name: '관평관',
      group: 'branch',
      grades: []
    },
    gwanjeo: {
      name: '관저관',
      group: 'branch',
      grades: []
    },
    areum: {
      name: '세종아름관',
      group: 'branch',
      grades: []
    },
    saerom: {
      name: '세종새롬관',
      group: 'branch',
      grades: []
    }
  };

  var MAIN_ORDER = ['elementary', 'middle', 'high', 'science', 'alpha', 'fullstory'];
  var BRANCH_ORDER = ['noeun', 'gwanpyeong', 'gwanjeo', 'areum', 'saerom'];

  function getScope() {
    var root = document.getElementById('schedule1001');
    return root ? (root.getAttribute('data-scope') || 'all') : 'all';
  }

  function getAcademyIds(scope) {
    if (scope === 'main') return MAIN_ORDER.slice();
    if (scope === 'branch') return BRANCH_ORDER.slice();
    return MAIN_ORDER.concat(BRANCH_ORDER);
  }

  function defaultAcademy(scope) {
    var ids = getAcademyIds(scope);
    for (var i = 0; i < ids.length; i++) {
      if (SCHEDULE_DATA[ids[i]].grades.length) return ids[i];
    }
    return ids[0];
  }

  function renderAcademies(scope, selectedId) {
    var mainWrap = document.getElementById('schAcademyMain');
    var branchWrap = document.getElementById('schAcademyBranch');
    var mainGroup = document.getElementById('schGroupMain');
    var branchGroup = document.getElementById('schGroupBranch');
    if (!mainWrap || !branchWrap) return;

    function makeButtons(ids, wrap) {
      wrap.innerHTML = '';
      ids.forEach(function (id) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.setAttribute('data-academy', id);
        btn.textContent = SCHEDULE_DATA[id].name;
        if (id === selectedId) btn.className = 'on';
        wrap.appendChild(btn);
      });
    }

    if (scope === 'branch') {
      if (mainGroup) mainGroup.style.display = 'none';
      if (branchGroup) branchGroup.style.display = '';
      makeButtons([], mainWrap);
      makeButtons(BRANCH_ORDER, branchWrap);
    } else if (scope === 'main') {
      if (mainGroup) mainGroup.style.display = '';
      if (branchGroup) branchGroup.style.display = 'none';
      makeButtons(MAIN_ORDER, mainWrap);
      makeButtons([], branchWrap);
    } else {
      if (mainGroup) mainGroup.style.display = '';
      if (branchGroup) branchGroup.style.display = '';
      makeButtons(MAIN_ORDER, mainWrap);
      makeButtons(BRANCH_ORDER, branchWrap);
    }
  }

  function renderGrades(academyId, selectedGradeId) {
    var wrap = document.getElementById('schGradeList');
    if (!wrap) return;
    var academy = SCHEDULE_DATA[academyId];
    wrap.innerHTML = '';

    if (!academy || !academy.grades.length) {
      var empty = document.createElement('button');
      empty.type = 'button';
      empty.disabled = true;
      empty.textContent = '학년 준비 중';
      wrap.appendChild(empty);
      return null;
    }

    var activeId = selectedGradeId;
    var found = academy.grades.some(function (g) { return g.id === activeId; });
    if (!found) activeId = academy.grades[0].id;

    academy.grades.forEach(function (grade) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.setAttribute('data-grade', grade.id);
      btn.textContent = grade.label;
      if (grade.id === activeId) btn.className = 'on';
      wrap.appendChild(btn);
    });
    return activeId;
  }

  function renderImages(academyId, gradeId) {
    var list = document.getElementById('schImgList');
    var empty = document.getElementById('schEmpty');
    var title = document.getElementById('schResultTit');
    if (!list || !empty || !title) return;

    var academy = SCHEDULE_DATA[academyId];
    if (!academy) return;

    var grade = null;
    if (gradeId) {
      for (var i = 0; i < academy.grades.length; i++) {
        if (academy.grades[i].id === gradeId) {
          grade = academy.grades[i];
          break;
        }
      }
    }

    list.innerHTML = '';

    if (!grade || !grade.images.length) {
      title.innerHTML = '더브코 <em>' + academy.name + '</em> 시간표';
      list.style.display = 'none';
      empty.style.display = '';
      empty.innerHTML = '<strong>시간표 준비 중</strong><p>해당 관의 시간표 이미지는 곧 업데이트됩니다.</p>';
      return;
    }

    title.innerHTML = '더브코 <em>' + academy.name + '</em> · ' + grade.label + ' 시간표';
    empty.style.display = 'none';
    list.style.display = '';

    grade.images.forEach(function (img) {
      var li = document.createElement('li');
      var image = document.createElement('img');
      image.src = img.src;
      image.alt = img.alt;
      image.loading = 'lazy';
      li.appendChild(image);
      list.appendChild(li);
    });
  }

  function setState(academyId, gradeId) {
    var root = document.getElementById('schedule1001');
    if (!root) return;
    root.setAttribute('data-academy', academyId);
    if (gradeId) root.setAttribute('data-grade', gradeId);
    else root.removeAttribute('data-grade');
  }

  function init() {
    var root = document.getElementById('schedule1001');
    if (!root) return;

    var scope = getScope();
    var academyId = root.getAttribute('data-academy') || defaultAcademy(scope);
    if (getAcademyIds(scope).indexOf(academyId) === -1) {
      academyId = defaultAcademy(scope);
    }

    renderAcademies(scope, academyId);
    var gradeId = renderGrades(academyId, root.getAttribute('data-grade'));
    setState(academyId, gradeId);
    renderImages(academyId, gradeId);

    root.addEventListener('click', function (e) {
      var academyBtn = e.target.closest('button[data-academy]');
      if (academyBtn) {
        academyId = academyBtn.getAttribute('data-academy');
        renderAcademies(scope, academyId);
        gradeId = renderGrades(academyId, null);
        setState(academyId, gradeId);
        renderImages(academyId, gradeId);
        return;
      }

      var gradeBtn = e.target.closest('button[data-grade]');
      if (gradeBtn) {
        gradeId = gradeBtn.getAttribute('data-grade');
        renderGrades(academyId, gradeId);
        setState(academyId, gradeId);
        renderImages(academyId, gradeId);
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
