import './Modal.css';

export function Modal({ title = 'Modal Title', body = 'Modal body content.', onClose = null, children = null } = {}) {
  const overlay = document.createElement('div');
  overlay.className = 'cspff-modal-overlay';
  overlay.addEventListener('click', () => {
    if (typeof onClose === 'function') onClose();
  });

  const modal = document.createElement('div');
  modal.className = 'cspff-modal';
  modal.addEventListener('click', (e) => e.stopPropagation());

  // Header
  const header = document.createElement('div');
  header.className = 'cspff-modal__header';

  const titleEl = document.createElement('div');
  titleEl.className = 'cspff-modal__title';
  titleEl.textContent = title;
  header.appendChild(titleEl);

  const closeBtn = document.createElement('button');
  closeBtn.className = 'cspff-modal__close';
  closeBtn.textContent = '×';
  closeBtn.addEventListener('click', () => {
    if (typeof onClose === 'function') onClose();
  });
  header.appendChild(closeBtn);

  modal.appendChild(header);

  // Body
  const bodyEl = document.createElement('div');
  bodyEl.className = 'cspff-modal__body';
  if (children) {
    bodyEl.appendChild(children);
  } else {
    bodyEl.textContent = body;
  }
  modal.appendChild(bodyEl);

  overlay.appendChild(modal);
  return overlay;
}

export const ModalMeta = {
  tokens: [
    '--component-modal-overlay',
    '--component-modal-bg',
  ],
};
