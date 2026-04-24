import './TopBar.css';

/**
 * @param {{ title?: string|null, crumbs?: string[], actions?: HTMLElement[] }} props
 */
export function TopBar({ title = null, crumbs = [], actions = [] } = {}) {
  const el = document.createElement('header');
  el.className = 'cspff-topbar';

  if (crumbs.length > 0) {
    const breadcrumb = document.createElement('div');
    breadcrumb.className = 'cspff-topbar__breadcrumb';

    crumbs.forEach((crumb, index) => {
      if (index > 0) {
        const sep = document.createElement('span');
        sep.className = 'cspff-topbar__sep';
        sep.textContent = '/';
        breadcrumb.appendChild(sep);
      }

      const crumbEl = document.createElement('span');
      const isLast = index === crumbs.length - 1;
      crumbEl.className = 'cspff-topbar__crumb' + (isLast ? '' : ' cspff-topbar__crumb--muted');
      crumbEl.textContent = crumb;
      breadcrumb.appendChild(crumbEl);
    });

    el.appendChild(breadcrumb);
  } else if (title) {
    const titleEl = document.createElement('span');
    titleEl.className = 'cspff-topbar__title';
    titleEl.textContent = title;
    el.appendChild(titleEl);
  }

  if (actions.length > 0) {
    const actionsEl = document.createElement('div');
    actionsEl.className = 'cspff-topbar__actions';
    actions.forEach(action => actionsEl.appendChild(action));
    el.appendChild(actionsEl);
  }

  return el;
}

export const TopBarMeta = {
  tokens: [
    '--component-topbar-bg',
    '--component-topbar-border',
    '--component-topbar-text',
    '--component-topbar-breadcrumb-separator',
  ],
};
