import { TopBar, TopBarMeta } from '../components/TopBar/TopBar.js';

export const TopBarStory = {
  title: 'TopBar',
  section: 'Components',
  meta: TopBarMeta,
  render(container) {
    const col = document.createElement('div');
    col.style.display = 'flex';
    col.style.flexDirection = 'column';
    col.style.gap = 'var(--spacing-6)';

    // Instance 1 — title only
    const wrap1 = document.createElement('div');
    wrap1.className = 'story-item';

    const card1 = document.createElement('div');
    card1.style.maxWidth = '600px';
    card1.style.borderRadius = 'var(--radius-lg)';
    card1.style.overflow = 'hidden';
    card1.style.boxShadow = 'var(--shadow-sm)';
    card1.appendChild(TopBar({ title: 'Global Dashboard' }));
    wrap1.appendChild(card1);

    const label1 = document.createElement('div');
    label1.className = 'story-label';
    label1.textContent = 'title only';
    wrap1.appendChild(label1);

    col.appendChild(wrap1);

    // Instance 2 — breadcrumb
    const wrap2 = document.createElement('div');
    wrap2.className = 'story-item';

    const card2 = document.createElement('div');
    card2.style.maxWidth = '600px';
    card2.style.borderRadius = 'var(--radius-lg)';
    card2.style.overflow = 'hidden';
    card2.style.boxShadow = 'var(--shadow-sm)';
    card2.appendChild(TopBar({ crumbs: ['Tenants', 'Acme Corp', 'Configuration'] }));
    wrap2.appendChild(card2);

    const label2 = document.createElement('div');
    label2.className = 'story-label';
    label2.textContent = 'breadcrumb';
    wrap2.appendChild(label2);

    col.appendChild(wrap2);

    container.appendChild(col);
  },
};
