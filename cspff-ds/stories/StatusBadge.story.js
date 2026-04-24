import { StatusBadge, StatusBadgeMeta } from '../components/StatusBadge/StatusBadge.js';

export const StatusBadgeStory = {
  title: 'StatusBadge',
  section: 'Components',
  meta: StatusBadgeMeta,
  render(container) {
    const statuses = ['active', 'trial', 'paused'];

    const row = document.createElement('div');
    row.className = 'story-row';

    statuses.forEach(status => {
      const wrap = document.createElement('div');
      wrap.className = 'story-item';
      // StatusBadge is absolute-positioned by default; use inline for story
      wrap.appendChild(StatusBadge({ status, inline: true }));

      const label = document.createElement('div');
      label.className = 'story-label';
      label.textContent = status;
      wrap.appendChild(label);

      const tokenLabel = document.createElement('div');
      tokenLabel.className = 'story-token';
      tokenLabel.textContent = `--component-status-badge-${status}-bg / -text`;
      wrap.appendChild(tokenLabel);

      row.appendChild(wrap);
    });

    container.appendChild(row);
  },
};
