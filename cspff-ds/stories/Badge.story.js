import { Badge, BadgeMeta } from '../components/Badge/Badge.js';

export const BadgeStory = {
  title: 'Badge',
  section: 'Components',
  meta: BadgeMeta,
  render(container) {
    const variants = ['active', 'trial', 'paused', 'default'];

    const row = document.createElement('div');
    row.className = 'story-row';

    variants.forEach(variant => {
      const wrap = document.createElement('div');
      wrap.className = 'story-item';

      wrap.appendChild(Badge({ variant, pip: true }));

      const label = document.createElement('div');
      label.className = 'story-label';
      label.textContent = variant;
      wrap.appendChild(label);

      const tokenLabel = document.createElement('div');
      tokenLabel.className = 'story-token';
      tokenLabel.textContent = `--component-badge-${variant}-bg / -text`;
      wrap.appendChild(tokenLabel);

      row.appendChild(wrap);
    });

    // No-pip variant
    const wrap = document.createElement('div');
    wrap.className = 'story-item';
    wrap.appendChild(Badge({ variant: 'active', pip: false, label: 'Active (no pip)' }));
    const lbl = document.createElement('div');
    lbl.className = 'story-label';
    lbl.textContent = 'no pip';
    wrap.appendChild(lbl);
    row.appendChild(wrap);

    container.appendChild(row);
  },
};
