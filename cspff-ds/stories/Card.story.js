import { Card, CardMeta } from '../components/Card/Card.js';

export const CardStory = {
  title: 'Card',
  section: 'Components',
  meta: CardMeta,
  render(container) {
    const row = document.createElement('div');
    row.className = 'story-row story-row--wide';

    // Default card
    const wrap1 = document.createElement('div');
    wrap1.className = 'story-item';
    wrap1.style.width = '320px';
    const defaultCard = Card({
      title: 'Card title',
      description: 'Secondary description text for the card section.',
    });
    const body1 = document.createElement('div');
    body1.className = 'cspff-card__body';
    body1.style.cssText = 'padding: var(--spacing-5); color: var(--semantic-color-text-muted); font-size: var(--typography-size-sm);';
    body1.textContent = 'Card body content area.';
    defaultCard.appendChild(body1);
    wrap1.appendChild(defaultCard);
    const lbl1 = document.createElement('div');
    lbl1.className = 'story-label';
    lbl1.textContent = 'default';
    wrap1.appendChild(lbl1);
    const tok1 = document.createElement('div');
    tok1.className = 'story-token';
    tok1.textContent = '--component-card-bg / --component-card-border';
    wrap1.appendChild(tok1);
    row.appendChild(wrap1);

    // Card (hover state — static demo)
    const wrap2 = document.createElement('div');
    wrap2.className = 'story-item';
    wrap2.style.width = '320px';
    const hoverCard = Card({ title: 'Card (hover)', description: 'Hover elevates with shadow-md.' });
    hoverCard.style.cssText = 'box-shadow: var(--shadow-md); transform: translateY(-1px);';
    const body2 = document.createElement('div');
    body2.className = 'cspff-card__body';
    body2.style.cssText = 'padding: var(--spacing-5); color: var(--semantic-color-text-muted); font-size: var(--typography-size-sm);';
    body2.textContent = 'Hover state shown statically.';
    hoverCard.appendChild(body2);
    wrap2.appendChild(hoverCard);
    const lbl2 = document.createElement('div');
    lbl2.className = 'story-label';
    lbl2.textContent = 'hover (static)';
    wrap2.appendChild(lbl2);
    const tok2 = document.createElement('div');
    tok2.className = 'story-token';
    tok2.textContent = '--shadow-md applied';
    wrap2.appendChild(tok2);
    row.appendChild(wrap2);

    container.appendChild(row);
  },
};
