# 그누보드5 완전 초보 가이드 (더브레인코어)

이 문서는 **그누보드를 처음 쓰는 분**을 위한 안내입니다.  
더브레인코어 홈페이지는 `apps/web` 디자인을 **그누보드 테마(tbc)** 로 이식해 두었습니다.

---

## 1. 그누보드가 뭔가요?

**그누보드**는 한국에서 많이 쓰는 **무료 홈페이지·게시판 프로그램**입니다.

| 비유 | 설명 |
|------|------|
| **건물 외관** | 지금 만든 HTML/CSS 디자인 (테마) — 바꾸지 않음 |
| **그누보드** | 건물 안의 **전기·수도·엘리베이터** (서버·DB·관리 시스템) |
| **관리자 페이지** | 사무실 — **공지, 강사 글, 상담 문의**를 직접 올리는 곳 |
| **게시판** | 공지사항, 수강후기 등 **글 목록이 모이는 방** |

코드를 몰라도 **관리자 화면에서 글 쓰기·사진 올리기**만 하면 사이트가 갱신됩니다.

---

## 2. 이 프로젝트 구조

```
TBC HOMPAGE/
├── apps/web/                    ← 디자인 원본 (HTML/CSS, Vercel 정적 배포)
├── gnuboard/
│   ├── theme-package/tbc/       ← 그누보드용 테마 (★ 핵심)
│   ├── docker-compose.yml       ← 로컬 서버 (PHP + MySQL)
│   └── runtime/                 ← 그누보드 프로그램 (설치 후 생성, git 제외)
└── docs/GNUBOARD_GUIDE.md       ← 이 파일
```

- **디자인 수정**: `apps/web` 에서 HTML/CSS 수정 → `npm run gnuboard:build` 로 테마에 반영
- **글·공지 수정**: 그누보드 **관리자**에서 수정 (코드 불필요)

---

## 3. 로컬에서 처음 실행하기 (Windows)

### 준비물

1. [Docker Desktop](https://www.docker.com/products/docker-desktop/) 설치 및 실행
2. [Node.js](https://nodejs.org/) (이미 있으면 생략)

### 한 번에 설정

프로젝트 폴더에서 PowerShell 또는 CMD:

```bash
npm run gnuboard:setup
```

이 명령이 하는 일:

1. `apps/web` → 테마(`theme-package/tbc`)로 CSS·이미지·페이지 복사
2. 그누보드5 프로그램 다운로드 (`gnuboard/runtime`)
3. TBC 테마 설치

### 서버 시작

```bash
npm run gnuboard:start
```

| 주소 | 용도 |
|------|------|
| http://localhost:8080 | 홈페이지 |
| http://localhost:8080/install/ | **최초 1회** 그누보드 설치 |
| http://localhost:8081 | phpMyAdmin (DB 확인용) |

### 그누보드 설치 (최초 1회)

1. http://localhost:8080/install/ 접속
2. 안내에 따라 **다음** 클릭
3. **DB 설정** 입력:

   | 항목 | 값 |
   |------|-----|
   | Host | `db` |
   | User | `gnuboard` |
   | Password | `gnuboard` |
   | DB 이름 | `gnuboard` |

4. 관리자 아이디·비밀번호·이메일 설정 (본인이 기억할 것!)
5. 설치 완료

### 테마 적용

1. http://localhost:8080/adm/ (관리자 로그인)
2. **환경설정** → **테마설정**
3. **tbc** 테마 선택 → 저장
4. http://localhost:8080 접속 → **더브레인코어 디자인**이 보이면 성공

---

## 4. 페이지는 어디로 연결되나요?

### 고정 페이지 (관·분원, 강사진, 시간표 등)

| 메뉴 | 그누보드 주소 예시 |
|------|-------------------|
| 인사말 | `/bbs/page.php?p=greeting` |
| 관·분원 | `/bbs/page.php?p=academies` |
| 강사진 | `/bbs/page.php?p=teachers` |
| 시간표 | `/bbs/page.php?p=schedule` |

→ 디자인은 `apps/web`과 동일합니다. 데이터만 나중에 DB와 연동할 수 있습니다.

### 게시판 (글을 올리는 곳)

관리자에서 **게시판을 먼저 만들어야** 메뉴가 동작합니다.

| 게시판 ID (bo_table) | 용도 | 메뉴 |
|---------------------|------|------|
| `notice` | 공지사항 | 더브코 소식 |
| `edu` | 교육·입시 정보 | 더브코 소식 |
| `review` | 수강후기 | 더브코 소식 |
| `consult` | 상담 신청 | 입학안내 |

#### 게시판 만드는 방법

1. 관리자 → **게시판관리** → **게시판 추가**
2. **게시판 ID**: 위 표의 영문 ID (예: `notice`)
3. **게시판 제목**: 공지사항
4. **스킨**: `basic` (기본) — 나중에 TBC 전용 스킨으로 바꿀 수 있음
5. 저장

4개 게시판을 각각 만들면 상단 메뉴의 **공지사항·교육정보·수강후기·상담신청** 링크가 작동합니다.

---

## 5. 일상적으로 하는 일

### A. 공지·글 올리기 (가장 흔함)

1. 관리자 로그인
2. **게시판** → 해당 게시판 선택
3. **글쓰기** → 제목·내용·이미지 입력 → 등록

→ 사이트에 바로 반영됩니다.

### B. 디자인·문구 수정 (개발)

1. `apps/web` 의 HTML/CSS 수정
2. 터미널에서:

```bash
npm run gnuboard:build
```

3. Docker가 켜져 있으면 `gnuboard/runtime/theme/tbc` 에 복사됨  
   (setup을 다시 실행하거나, runtime/theme/tbc 를 theme-package에서 복사)

간단히: **setup 한 번 후**에는 `gnuboard:build` 후 runtime 테마 폴더를 덮어쓰면 됩니다.

```bash
# runtime 테마 갱신 (Windows)
robocopy gnuboard\theme-package\tbc gnuboard\runtime\theme\tbc /E /MIR
```

### C. 서버 끄기

```bash
npm run gnuboard:stop
```

---

## 6. 실제 호스팅(카페24 등)으로 옮기기

로컬에서 **글·디자인·게시판**을 다 확인한 뒤:

### 옮기는 것

| 항목 | 방법 |
|------|------|
| **파일** | FTP로 `gnuboard/runtime` 전체 (또는 호스팅에 그누보드 설치 후 `theme/tbc` + `bbs/page.php` 업로드) |
| **DB** | phpMyAdmin → SQL보내기 → 호스팅 DB에 가져오기 |
| **설정** | `data/dbconfig.php` 의 DB 접속 정보를 호스팅 정보로 수정 |
| **URL** | 관리자 → 환경설정 → **사이트 URL**을 실제 도메인으로 변경 |

### 호스팅 요구사항

- PHP 7.4 이상 (8.x 권장)
- MySQL 5.7 / 8.0
- 카페24·가비아·닷홈 리눅스 호스팅이면 대부분 가능

---

## 7. 자주 묻는 질문

### Q. Vercel 사이트와 그누보드는 같이 쓰나요?

- **지금**: Vercel = 정적 미리보기, 로컬 Docker = 그누보드 개발
- **나중**: 실제 운영은 **그누보드 호스팅 한 곳**으로 통일하는 것을 권장

### Q. 디자인이 깨질까요?

- 테마가 `apps/web` CSS·class·이미지 경로를 그대로 씁니다.
- 디자인 변경은 `apps/web`만 수정하고 `gnuboard:build` 하면 됩니다.

### Q. 관리자 주소가 뭔가요?

- 로컬: http://localhost:8080/adm/
- 설치 시 만든 **관리자 계정**으로 로그인

### Q. 비밀번호를 잊었어요

- phpMyAdmin에서 `g5_member` 테이블 확인하거나, `install` 폴더로 재설치 (DB 초기화 주의)

---

## 8. 명령어 요약

| 명령 | 설명 |
|------|------|
| `npm run gnuboard:setup` | 그누보드 다운로드 + 테마 설치 (최초 1회) |
| `npm run gnuboard:build` | apps/web → 테마 변환 |
| `npm run gnuboard:start` | Docker 로컬 서버 시작 |
| `npm run gnuboard:stop` | Docker 서버 종료 |

---

## 9. 다음에 할 수 있는 것

- [ ] 게시판 4개 생성 (notice, edu, review, consult)
- [ ] 메인 공지 영역을 `notice` 게시판 최신글과 연동
- [ ] 강사진을 게시판(갤러리)으로 관리
- [ ] 실제 전화번호·주소·사업자번호 관리자에서 수정
- [ ] 카페24 등 호스팅 이전

궁금한 점이 있으면 Agent 모드에서 “게시판 연동해줘”, “호스팅 이전 도와줘”라고 요청하시면 됩니다.
