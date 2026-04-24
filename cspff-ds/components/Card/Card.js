import './Card.css';

/**
 * @param {{
 *   title?: string,
 *   description?: string,
 *   children?: HTMLElement[],
 * }} props
 */
export function Card({ title, description, children = [] } = {}) {
  const el = document.createElement('div');
  el.className = 'cspff-card';
  el.setAttribute('role', 'region');

  if (title) {
    const header = document.createElement('header');
    header.className = 'cspff-card__header';

    const h = document.createElement('h2');
    h.className = 'cspff-card__title';
    h.textContent = title;
    header.appendChild(h);

    if (description) {
      const p = document.createElement('p');
      p.className = 'cspff-card__description';
      p.textContent = description;
      header.appendChild(p);
    }

    el.appendChild(header);
  }

  if (children.length) {
    const body = document.createElement('div');
    body.className = 'cspff-card__body';
    children.forEach(c => body.appendChild(c));
    el.appendChild(body);
  }

  return el;
}

export const CardMeta = {
  tokens: [
    '--component-card-bg',
    '--component-card-border',
  ],
};
