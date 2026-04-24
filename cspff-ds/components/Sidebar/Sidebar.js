import './Sidebar.css';

/**
 * @param {{ sections?: Array<{ label: string|null, items: Array<{ id: string, label: string }> }>, activeItem?: string|null }} props
 */
export function Sidebar({ sections = [], activeItem = null } = {}) {
  const el = document.createElement('nav');
  el.className = 'cspff-sidebar';

  sections.forEach(section => {
    if (section.label) {
      const sectionLabel = document.createElement('div');
      sectionLabel.className = 'cspff-sidebar__section-label';
      sectionLabel.textContent = section.label;
      el.appendChild(sectionLabel);
    }

    section.items.forEach(item => {
      const btn = document.createElement('button');
      btn.className = 'cspff-sidebar__item' + (item.id === activeItem ? ' cspff-sidebar__item--active' : '');
      btn.textContent = item.label;
      el.appendChild(btn);
    });
  });

  return el;
}

export const SidebarMeta = {
  tokens: [
    '--component-sidebar-bg',
    '--component-sidebar-item-text',
    '--component-sidebar-item-active-bg',
    '--component-sidebar-item-active-text',
    '--component-sidebar-section-label-text',
  ],
};
