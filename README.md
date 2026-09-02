# 더브레인코어 홈페이지

## 로컬에서 보기

`html예시파일` 폴더에서 정적 HTML로 바로 확인할 수 있습니다.

1. `html예시파일/index.html` 파일을 브라우저에서 엽니다.
2. 또는 `html예시파일` 폴더에서 로컬 서버를 실행합니다.

```bash
cd html예시파일
npx serve .
```

## 에셋

| 용도 | 원본 위치 | 사이트에서 사용 |
|------|-----------|----------------|
| 로고 (가로) | `assets/logos/로고 가로버전.png` | `html예시파일/img/common/logo.png` |
| 푸터 로고 | 동일 (흰색 버전 추후 교체 가능) | `html예시파일/img/common/logo_w.png` |
| 교육 정보 배너 | `assets/images/banners/1.png`, `2.png` | `html예시파일/img/main/inc03/banner01.png`, `banner02.png` |

로고·배너를 바꿀 때는 `assets/`에 파일을 넣은 뒤 위 경로로 복사하면 됩니다.

## 구조에 대한 안내

`html예시파일/`은 **현재 단계의 메인 페이지 원본**입니다. 디자인·레이아웃의 기준(Source of Truth)으로 두고, 지금처럼 HTML에서 문구·이미지만 바꿔가며 맞추는 방식이 맞습니다.

다만 **공지·강사·배너 등 DB 데이터**를 넣을 단계가 오면, 이 HTML을 그대로 영구 운영하기보다는:

1. **Next.js**로 옮기되 `css/`, `class`명, DOM 구조는 그대로 유지
2. 바뀌는 부분만 API(MongoDB Atlas)에서 fetch
3. **관리자 페이지**에서 배너·강사 사진 등 업로드

이 순서가 Vercel(프론트) + Render(API) 계획과 잘 맞습니다.  
**지금은 html예시파일을 디자인 검증용으로 유지**하고, 데이터 연동이 필요해질 때 프레임워크로 이식하는 것을 권장합니다.

## 메뉴 구조

| 메뉴 | 하위 메뉴 |
|------|-----------|
| 더브코 | 인사말, 교육철학, 연혁 |
| 관·분원 | 본원 · 대전 둔산동, 분원 안내, 전체 관·분원 |
| 강사진 | 국어, 수학, 영어 |
| 시간표 | 본원 시간표, 분원 시간표, 전체 시간표 |
| 더브코 소식 | 공지사항, 교육·입시정보, 수강후기 |
| 입학안내 | 상담신청, 입학절차, FAQ |

상단 우측 **상담신청** CTA 포함.

## Vercel 배포

1. [Vercel](https://vercel.com)에서 GitHub 저장소 `TBC-HOMEPAGE` 연결
2. **Root Directory**를 `html예시파일`로 설정
3. Framework Preset: **Other** (빌드 명령 없음)
4. Deploy

저장소: https://github.com/changhyun152521/TBC-HOMEPAGE

## 포함된 파일

- `html예시파일/` — 사이트 전체 (HTML, CSS, JS, 이미지)
- `assets/` — 원본 로고, 배너, 폰트 보관함
