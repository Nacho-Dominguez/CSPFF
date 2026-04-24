import './Badge.css';

const VARIANT_MAP = {
  active:  { bg: 'var(--component-badge-active-bg)',  text: 'var(--component-badge-active-text)'  },
  trial:   { bg: 'var(--component-badge-trial-bg)',   text: 'var(--component-badge-trial-text)'   },
  paused:  { bg: 'var(--component-badge-paused-bg)',  text: 'var(--component-badge-paused-text)'  },
  default: { bg: 'var(--component-badge-default-bg)', text: 'var(--component-badge-default-text)' },
};

/**
 * @param {{ variant?: 'active'|'trial'|'paused'|'default', label?: string, pip?: boolean }} props
 */
export function Badge({ variant = 'default', label, pip = true } = {}) {
  const colors = VARIANT_MAP[variant] ?? VARIANT_MAP.default;
  const displayLabel = label ?? ({ active: 'Active', trial: 'Trial', paused: 'Paused', default: 'Default' }[variant]);

  const el = document.createElement('span');
  el.className = 'cspff-badge';
  el.style.setProperty('--badge-bg',   colors.bg);
  el.style.setProperty('--badge-text', colors.text);

  if (pip) {
    const dot = document.createElement('span');
    dot.className = 'cspff-badge__pip';
    el.appendChild(dot);
  }

  el.appendChild(document.createTextNode(displayLabel));
  return el;
}

export const BadgeMeta = {
  tokens: [
    '--component-badge-active-bg',
    '--component-badge-active-text',
    '--component-badge-trial-bg',
    '--component-badge-trial-text',
    '--component-badge-paused-bg',
    '--component-badge-paused-text',
    '--component-badge-default-bg',
    '--component-badge-default-text',
  ],
};
