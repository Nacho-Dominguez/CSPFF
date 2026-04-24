import { ModalMeta } from '../components/Modal/Modal.js';
import '../components/Modal/Modal.css';

function ModalCard({ title, body, titleStyle = null } = {}) {
  const modal = document.createElement('div');
  modal.className = 'cspff-modal';

  // Header
  const header = document.createElement('div');
  header.className = 'cspff-modal__header';

  const titleEl = document.createElement('div');
  titleEl.className = 'cspff-modal__title';
  titleEl.textContent = title;
  if (titleStyle) {
    Object.assign(titleEl.style, titleStyle);
  }
  header.appendChild(titleEl);

  const closeBtn = document.createElement('button');
  closeBtn.className = 'cspff-modal__close';
  closeBtn.textContent = '×';
  header.appendChild(closeBtn);

  modal.appendChild(header);

  // Body
  const bodyEl = document.createElement('div');
  bodyEl.className = 'cspff-modal__body';
  bodyEl.textContent = body;
  modal.appendChild(bodyEl);

  // Footer
  const footer = document.createElement('div');
  footer.className = 'cspff-modal__footer';
  modal.appendChild(footer);

  return modal;
}

export const ModalStory = {
  title: 'Modal',
  section: 'Components',
  meta: ModalMeta,
  render(container) {
    const row = document.createElement('div');
    row.className = 'story-row';

    // Default variant
    const item1 = document.createElement('div');
    item1.className = 'story-item';
    const lbl1 = document.createElement('div');
    lbl1.className = 'story-label';
    lbl1.textContent = 'default';
    item1.appendChild(lbl1);

    const stage1 = document.createElement('div');
    stage1.style.cssText = 'position: relative; height: 300px; background: var(--semantic-color-bg-page, #e6eaf0); border-radius: var(--radius-lg); overflow: hidden; display: flex; align-items: center; justify-content: center;';
    stage1.appendChild(ModalCard({
      title: 'Confirm Action',
      body: 'Are you sure you want to proceed? This action cannot be undone.',
    }));
    item1.appendChild(stage1);
    row.appendChild(item1);

    // Destructive variant
    const item2 = document.createElement('div');
    item2.className = 'story-item';
    const lbl2 = document.createElement('div');
    lbl2.className = 'story-label';
    lbl2.textContent = 'destructive';
    item2.appendChild(lbl2);

    const stage2 = document.createElement('div');
    stage2.style.cssText = 'position: relative; height: 300px; background: var(--semantic-color-bg-page, #e6eaf0); border-radius: var(--radius-lg); overflow: hidden; display: flex; align-items: center; justify-content: center;';
    stage2.appendChild(ModalCard({
      title: 'Delete Tenant',
      body: 'This will permanently remove the tenant and all associated data.',
      titleStyle: { color: 'var(--semantic-color-interactive-danger)' },
    }));
    item2.appendChild(stage2);
    row.appendChild(item2);

    container.appendChild(row);

    // Token reference
    const tokenRow = document.createElement('div');
    tokenRow.className = 'story-row';
    ModalMeta.tokens.forEach((token) => {
      const chip = document.createElement('span');
      chip.className = 'story-token';
      chip.textContent = token;
      tokenRow.appendChild(chip);
    });
    container.appendChild(tokenRow);
  },
};
