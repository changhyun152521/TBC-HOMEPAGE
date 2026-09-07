# 배포 안내

## Vercel (정적 사이트)

1. [Vercel](https://vercel.com) → GitHub `TBC-HOMEPAGE` 연결
2. **Root Directory**: `apps/web`
3. Framework Preset: **Other** (빌드 명령 없음)
4. Deploy

수정은 **`apps/web`만** 편집한 뒤 push하면 자동 배포됩니다.

## 그누보드 (CMS)

로컬 개발·호스팅 이전은 [GNUBOARD_GUIDE.md](GNUBOARD_GUIDE.md)를 참고하세요.

| 명령 | 설명 |
|------|------|
| `npm run gnuboard:setup` | 로컬 그누보드 설치 |
| `npm run gnuboard:start` | Docker 서버 시작 |
| `npm run gnuboard:build` | apps/web → 그누보드 테마 반영 |

## Vercel Root 변경 (기존 `html예시파일` 사용 중이었다면)

1. Vercel → Project Settings → General → **Root Directory** → `apps/web`
2. Save 후 Redeploy

이후 `html예시파일` 폴더는 저장소에 없습니다. `apps/web`만 사용합니다.
