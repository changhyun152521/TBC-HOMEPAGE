# 그누보드 로컬 개발

상세 가이드: [docs/GNUBOARD_GUIDE.md](../docs/GNUBOARD_GUIDE.md)

## 빠른 시작

```bash
# 1. Docker Desktop 실행
# 2. 최초 설정
npm run gnuboard:setup

# 3. 서버 시작
npm run gnuboard:start

# 4. 브라우저
#    - 설치: http://localhost:8080/install/
#    - DB: host=db, user=gnuboard, pass=gnuboard, db=gnuboard
#    - 관리자: http://localhost:8080/adm/ → 테마 tbc 선택
```

## 테마 위치

- 소스: `gnuboard/theme-package/tbc/`
- `apps/web` 수정 후: `npm run gnuboard:build`
