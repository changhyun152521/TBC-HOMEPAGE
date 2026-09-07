# 배포 안내

## 현재 (Vercel Root: `html예시파일`)

GitHub 푸시 시 Vercel이 `html예시파일/`을 배포합니다.

**사이트 수정 시** `apps/web/`만 편집한 뒤 아래 명령으로 동기화하세요.

```bash
npm run sync:legacy
git add apps/web html예시파일
git commit -m "..."
git push
```

## 권장 (Vercel Root: `apps/web`)

Vercel 프로젝트 설정에서 **Root Directory**를 `apps/web`으로 바꾸면 `html예시파일` 복사본 없이 배포할 수 있습니다.

1. Vercel → Project Settings → General → Root Directory → `apps/web`
2. 저장소에서 `html예시파일/` 폴더 삭제 (선택)
3. 이후 `apps/web`만 수정·커밋
