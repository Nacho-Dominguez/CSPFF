import './StatusBadge.css';

const STATUS_MAP = {
  active:  { bg: 'var(--component-status-badge-active-bg)',  text: 'var(--component-status-badge-active-text)'  },
  paused:  { bg: 'var(--component-status-badge-paused-bg)',  text: 'var(--component-status-badge-paused-text)'  },
  trial:   { bg: 'var(--component-status-badge-trial-bg)',   text: 'var(--component-status-badge-trial-text)'   },
};

/**
 * @param {{ status: 'active'|'paused'|'trial', inline?: boolean }} props
 */
export function StatusBadge({ status = 'active', inline = false } = {}) {
  const colors = STATUS_MAP[status] ?? STATUS_MAP.active;
  const label = { active: 'Active', paused: 'Paused', trial: 'Trial' }[status];

  const el = document.createElement('span');
  el.className = `cspff-status-badge${inline ? ' cspff-status-badge--inline' : ''}`;
  el.style.setProperty('--status-badge-bg',   colors.bg);
  el.style.setProperty('--status-badge-text', colors.text);

  const pip = document.createElement('span');
  pip.className = 'cspff-status-badge__pip';
  el.appendChild(pip);
  el.appendChild(document.createTextNode(label));
  return el;
}

export const StatusBadgeMeta = {
  tokens: [
    '--component-status-badge-active-bg',
    '--component-status-badge-active-text',
    '--component-status-badge-paused-bg',
    '--component-status-badge-paused-text',
    '--component-status-badge-trial-bg',
    '--component-status-badge-trial-text',
  ],
};
