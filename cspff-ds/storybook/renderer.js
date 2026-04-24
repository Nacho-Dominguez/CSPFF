import * as Stories from '../stories/index.js';

// All stories as a flat array, preserving import order
const ALL_STORIES = Object.values(Stories);

// Nav structure: Foundations first, then Components
const FOUNDATIONS = ['Color', 'Spacing', 'Typography', 'Radius', 'Shadow'];

// ─── State ───────────────────────────────────────────────────────────────────
let activeStoryTitle = ALL_STORIES[0]?.title ?? null;
let navCollapsed = false;

// ─── DOM refs (populated in init) ────────────────────────────────────────────
let navEl, navBodyEl, canvasEl, inspectorBodyEl, toolbarTitleEl, toolbarSectionEl;
let copyBtnEl, searchInputEl;

// ─── Render helpers ──────────────────────────────────────────────────────────

function getActiveStory() {
  return ALL_STORIES.find(s => s.title === activeStoryTitle) ?? null;
}

function renderNav(filter = '') {
  navBodyEl.innerHTML = '';

  const sections = {};
  ALL_STORIES.forEach(story => {
    const sec = story.section ?? 'Components';
    if (!sections[sec]) sections[sec] = [];
    sections[sec].push(story);
  });

  const sectionOrder = ['Components'];
  Object.keys(sections).forEach(k => { if (!sectionOrder.includes(k)) sectionOrder.push(k); });

  sectionOrder.forEach(sectionName => {
    const items = (sections[sectionName] ?? []).filter(s =>
      !filter || s.title.toLowerCase().includes(filter.toLowerCase())
    );
    if (!items.length) return;

    const labelEl = document.createElement('div');
    labelEl.className = 'sb-nav__section-label';
    labelEl.textContent = sectionName;
    navBodyEl.appendChild(labelEl);

    items.forEach(story => {
      const btn = document.createElement('button');
      btn.className = 'sb-nav__item' + (story.title === activeStoryTitle ? ' sb-nav__item--active' : '');
      btn.textContent = story.title;
      btn.addEventListener('click', () => {
        activeStoryTitle = story.title;
        renderNav(searchInputEl.value);
        renderCanvas();
        renderInspector();
      });
      navBodyEl.appendChild(btn);
    });
  });
}

function renderCanvas() {
  canvasEl.innerHTML = '';
  const story = getActiveStory();

  if (!story) {
    const empty = document.createElement('div');
    empty.className = 'sb-canvas__empty';
    empty.textContent = 'Select a story from the sidebar.';
    canvasEl.appendChild(empty);
    return;
  }

  toolbarTitleEl.textContent = story.title;
  toolbarSectionEl.textContent = story.section ?? 'Components';

  const container = document.createElement('div');
  story.render(container);
  canvasEl.appendChild(container);
}

function renderInspector() {
  inspectorBodyEl.innerHTML = '';
  const story = getActiveStory();

  if (!story?.meta?.tokens?.length) {
    const empty = document.createElement('div');
    empty.className = 'sb-inspector__empty';
    empty.textContent = 'No tokens for this story.';
    inspectorBodyEl.appendChild(empty);
    return;
  }

  story.meta.tokens.forEach(tokenName => {
    const row = document.createElement('div');
    row.className = 'sb-inspector__token';

    const nameEl = document.createElement('span');
    nameEl.className = 'sb-inspector__token-name';
    nameEl.textContent = tokenName;

    const copyEl = document.createElement('span');
    copyEl.className = 'sb-inspector__token-copy';
    copyEl.textContent = 'copy';

    row.appendChild(nameEl);
    row.appendChild(copyEl);

    row.addEventListener('click', () => {
      navigator.clipboard?.writeText(tokenName).catch(() => {});
      row.classList.add('sb-inspector__token--copied');
      copyEl.textContent = '✓';
      setTimeout(() => {
        row.classList.remove('sb-inspector__token--copied');
        copyEl.textContent = 'copy';
      }, 1500);
    });

    inspectorBodyEl.appendChild(row);
  });
}

// ─── Copy tokens button ───────────────────────────────────────────────────────

async function copyAllTokens() {
  try {
    const res = await fetch('/tokens/reference.css');
    const text = await res.text();
    await navigator.clipboard.writeText(text);
    copyBtnEl.textContent = '✓ Copied!';
    copyBtnEl.classList.add('sb-nav__copy-btn--copied');
    setTimeout(() => {
      copyBtnEl.textContent = 'Copy tokens';
      copyBtnEl.classList.remove('sb-nav__copy-btn--copied');
    }, 2000);
  } catch {
    copyBtnEl.textContent = 'Error';
    setTimeout(() => { copyBtnEl.textContent = 'Copy tokens'; }, 2000);
  }
}

// ─── Build shell ──────────────────────────────────────────────────────────────

function buildShell() {
  document.body.innerHTML = '';
  document.body.className = '';

  const app = document.createElement('div');
  app.className = 'sb-app';

  // ── Nav ──
  navEl = document.createElement('nav');
  navEl.className = 'sb-nav';

  const navHeader = document.createElement('div');
  navHeader.className = 'sb-nav__header';

  const logo = document.createElement('div');
  logo.className = 'sb-nav__logo';
  logo.textContent = 'CSPFF Design System';

  const searchWrap = document.createElement('div');
  searchWrap.className = 'sb-nav__search';

  const searchIcon = document.createElement('span');
  searchIcon.className = 'sb-nav__search-icon';
  searchIcon.textContent = '⌕';

  searchInputEl = document.createElement('input');
  searchInputEl.className = 'sb-nav__search-input';
  searchInputEl.type = 'text';
  searchInputEl.placeholder = 'Search…';
  searchInputEl.addEventListener('input', () => renderNav(searchInputEl.value));

  searchWrap.appendChild(searchIcon);
  searchWrap.appendChild(searchInputEl);
  navHeader.appendChild(logo);
  navHeader.appendChild(searchWrap);

  navBodyEl = document.createElement('div');
  navBodyEl.className = 'sb-nav__body';

  const navFooter = document.createElement('div');
  navFooter.className = 'sb-nav__footer';

  copyBtnEl = document.createElement('button');
  copyBtnEl.className = 'sb-nav__copy-btn';
  copyBtnEl.textContent = 'Copy tokens';
  copyBtnEl.addEventListener('click', copyAllTokens);
  navFooter.appendChild(copyBtnEl);

  navEl.appendChild(navHeader);
  navEl.appendChild(navBodyEl);
  navEl.appendChild(navFooter);

  // ── Main ──
  const mainEl = document.createElement('div');
  mainEl.className = 'sb-main';

  const toolbar = document.createElement('div');
  toolbar.className = 'sb-toolbar';

  const toggleBtn = document.createElement('button');
  toggleBtn.className = 'sb-toolbar__toggle';
  toggleBtn.setAttribute('aria-label', 'Toggle sidebar');
  toggleBtn.textContent = '☰';
  toggleBtn.addEventListener('click', () => {
    navCollapsed = !navCollapsed;
    navEl.classList.toggle('sb-nav--collapsed', navCollapsed);
  });

  toolbarTitleEl = document.createElement('span');
  toolbarTitleEl.className = 'sb-toolbar__title';

  toolbarSectionEl = document.createElement('span');
  toolbarSectionEl.className = 'sb-toolbar__section';

  toolbar.appendChild(toggleBtn);
  toolbar.appendChild(toolbarTitleEl);
  toolbar.appendChild(toolbarSectionEl);

  const canvasWrap = document.createElement('div');
  canvasWrap.className = 'sb-canvas-wrap';

  canvasEl = document.createElement('div');
  canvasEl.className = 'sb-canvas';

  // ── Inspector ──
  const inspectorEl = document.createElement('aside');
  inspectorEl.className = 'sb-inspector';

  const inspectorHeader = document.createElement('div');
  inspectorHeader.className = 'sb-inspector__header';
  inspectorHeader.textContent = 'Token Inspector';

  inspectorBodyEl = document.createElement('div');
  inspectorBodyEl.className = 'sb-inspector__body';

  inspectorEl.appendChild(inspectorHeader);
  inspectorEl.appendChild(inspectorBodyEl);

  canvasWrap.appendChild(canvasEl);
  canvasWrap.appendChild(inspectorEl);

  mainEl.appendChild(toolbar);
  mainEl.appendChild(canvasWrap);

  app.appendChild(navEl);
  app.appendChild(mainEl);
  document.body.appendChild(app);
}

// ─── Init ─────────────────────────────────────────────────────────────────────

export function init() {
  buildShell();
  renderNav();
  renderCanvas();
  renderInspector();
}
