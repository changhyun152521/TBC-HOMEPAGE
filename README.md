# 더브레인코어 홈페이지

## 로컬에서 보기

`apps/web` 폴더에서 정적 HTML로 바로 확인할 수 있습니다.

1. `apps/web/index.html` 파일을 브라우저에서 엽니다.
2. 또는 `apps/web` 폴더에서 로컬 서버를 실행합니다.

```bash
cd apps/web
npx serve .
```

## 폴더 구조

| 경로 | 용도 |
|------|------|
| `apps/web/` | **배포용 사이트** (HTML, CSS, JS, 이미지) |
| `design/` | 디자인 참고 원본 (teachers, academies, schedule) |
| `assets/` | 원본 로고, 배너, 폰트 보관함 |
| `docs/` | 프로젝트 문서 |

자세한 구조는 [docs/STRUCTURE.md](docs/STRUCTURE.md)를 참고하세요.

## 에셋

| 용도 | 원본 위치 | 사이트에서 사용 |
|------|-----------|----------------|
| 로고 (가로) | `assets/logos/로고 가로버전.png` | `apps/web/img/common/logo.png` |
| 푸터 로고 | 동일 (흰색 버전 추후 교체 가능) | `apps/web/img/common/logo_w.png` |
| 교육 정보 배너 | `assets/images/banners/1.png`, `2.png` | `apps/web/img/main/inc03/banner01.png`, `banner02.png` |

로고·배너를 바꿀 때는 `assets/`에 파일을 넣은 뒤 위 경로로 복사하면 됩니다.

## 구조에 대한 안내

`apps/web/`은 **현재 단계의 메인 페이지**입니다. HTML 구조·클래스명을 유지해 그누보드 이전이나 Next.js 이식 시에도 디자인이 깨지지 않도록 합니다.

공지·강사·배너 등 DB 데이터가 필요해지면:

1. **Next.js** 또는 **그누보드**로 옮기되 `css/`, `class`명, DOM 구조는 그대로 유지
2. 바뀌는 부분만 API/DB에서 fetch
3. **관리자 페이지**에서 배너·강사 사진 등 업로드

## 메뉴 구조

| 메뉴 | 하위 메뉴 |
|------|-----------|
| 더브코 | 인사말, 교육철학, 연혁 |
| 관·분원 | 본원 · 대전 둔산, 분원 안내, 전체 관·분원 |
| 강사진 | 전체 강사진, 국어, 수학, 영어, 사회, 과학 |
| 시간표 | 본원 시간표, 분원 시간표, 전체 시간표 |
| 더브코 소식 | 공지사항, 교육·입시정보, 수강후기 |
| 입학안내 | 상담신청, 입학절차, FAQ |

상단 우측 **상담신청** CTA 포함.

## Vercel 배포

1. [Vercel](https://vercel.com)에서 GitHub 저장소 `TBC-HOMEPAGE` 연결
2. **Root Directory**를 `apps/web`로 설정 (권장) 또는 기존 `html예시파일` 유지
3. Framework Preset: **Other** (빌드 명령 없음)
4. Deploy

기존 `html예시파일` Root를 쓰는 경우, 수정은 `apps/web`에서 하고 `npm run sync:legacy`로 동기화합니다. 자세한 내용은 [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md).

저장소: https://github.com/changhyun152521/TBC-HOMEPAGE
