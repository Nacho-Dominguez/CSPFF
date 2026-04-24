# CSPFF Design System

Vite-powered design system for the CSPFF multi-tenant driver safety education platform. Built by Dual Boot Partners.

## Local development

```bash
npm install
npm run dev
# → http://localhost:5173
```

## Build

```bash
npm run build
# Output: dist/
```

## Deploy to Vercel

### Option 1 — Git integration (recommended)
1. Push this repo to GitHub
2. Go to vercel.com → New Project → Import `cspff-ds`
3. Vercel auto-detects `vercel.json` — no config needed
4. Deploy

### Option 2 — Vercel CLI
```bash
npm install -g vercel
npm run build
vercel --prod
```

## Structure

```
tokens/
  primitives.css   ← OKLCH color scales, spacing, radius, typography, shadow
  semantic.css     ← color intent mapping (surfaces, text, borders, status, interactive)
  components.css   ← per-component color tokens
  index.css        ← imports all three in order
  reference.css    ← flat token list for copy-paste into Claude Design prompts

components/        ← 11 vanilla JS components, each with .js + .css
stories/           ← 11 story files + index.js
storybook/         ← renderer.js (Storybook UI) + layout.css (chrome styles only)
```

## Token architecture

Color follows a strict three-level hierarchy:

```
Primitives → Semantics → Component tokens → Components
```

Components only reference `--component-*` vars for color.
Everything else (spacing, radius, shadow, typography) uses raw scale steps directly:
`var(--spacing-4)`, `var(--radius-md)`, `var(--shadow-sm)`

## Copying tokens for Claude Design

Open the Storybook → click **Copy tokens** in the sidebar.
Paste into your Claude Design prompt to maintain consistency.
