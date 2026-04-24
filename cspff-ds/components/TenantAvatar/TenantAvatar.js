import './TenantAvatar.css';

export function TenantAvatar({ initials = '?', size = 'default', bgColor = null } = {}) {
  const el = document.createElement('div');

  const classes = ['cspff-avatar'];
  if (size === 'sm') classes.push('cspff-avatar--sm');
  if (size === 'lg') classes.push('cspff-avatar--lg');
  el.className = classes.join(' ');

  el.style.setProperty('--avatar-bg', bgColor ?? 'var(--component-tenant-avatar-bg)');
  el.style.setProperty('--avatar-text', 'var(--component-tenant-avatar-text)');

  el.textContent = initials;

  return el;
}

export const TenantAvatarMeta = {
  tokens: [
    '--component-tenant-avatar-bg',
    '--component-tenant-avatar-text',
  ],
};
