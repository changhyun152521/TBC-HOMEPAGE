# 프로젝트 폴더 구조

```
TBC-HOMEPAGE/
├── apps/
│   └── web/              # 배포용 정적 사이트 (HTML/CSS/JS 유지)
├── gnuboard/
│   ├── theme-package/tbc/  # 그누보드5 TBC 테마
│   ├── docker-compose.yml  # 로컬 PHP+MySQL
│   └── runtime/            # 그누보드 프로그램 (설치 후, git 제외)
├── design/               # 디자인 참고 원본 (배포 제외)
│   ├── teachers/         # 강사진 상세페이지 원본
│   ├── academies/        # 관·분원 상세페이지 원본
│   └── schedule/         # 시간표 상세페이지 원본
├── assets/               # 로고·배너 등 원본 에셋
├── docs/                 # 프로젝트 문서
└── apps/web/vercel.json  # Vercel 배포 설정
```

## apps/web

- **그누보드 이전 대비**: HTML 마크업·클래스명·상대경로(`css/`, `img/`, `js/`)를 그대로 유지합니다.
- 로컬 확인: `apps/web/index.html`을 브라우저에서 열거나 `cd apps/web && npx serve .`

## design/

- 퍼블리셔·디자이너가 전달한 **참고용** HTML/CSS입니다.
- 실제 사이트는 `apps/web`만 수정·배포합니다.

## assets/

- 원본 로고·배너 보관. 사이트에 반영할 때 `apps/web/img/`로 복사합니다.
