import { Sidebar, SidebarMeta } from '../components/Sidebar/Sidebar.js';

const SECTIONS = [
  { label: null, items: [{ id: 'dashboard', label: 'Dashboard' }, { id: 'tenants', label: 'Tenants' }, { id: 'drivers', label: 'Drivers' }] },
  { label: 'Settings', items: [{ id: 'users', label: 'Users' }, { id: 'config', label: 'Configuration' }] },
];

export const SidebarStory = {
  title: 'Sidebar',
  section: 'Components',
  meta: SidebarMeta,
  render(container) {
    const row = document.createElement('div');
    row.className = 'story-row';

    // Instance 1 — Dashboard active
    const wrap1 = document.createElement('div');
    wrap1.className = 'story-item';
    wrap1.appendChild(Sidebar({ sections: SECTIONS, activeItem: 'dashboard' }));
    const label1 = document.createElement('div');
    label1.className = 'story-label';
    label1.textContent = 'Global / Dashboard active';
    wrap1.appendChild(label1);
    row.appendChild(wrap1);

    // Instance 2 — Courses active (id not in sections, shows no active state)
    const wrap2 = document.createElement('div');
    wrap2.className = 'story-item';
    wrap2.appendChild(Sidebar({ sections: SECTIONS, activeItem: 'courses' }));
    const label2 = document.createElement('div');
    label2.className = 'story-label';
    label2.textContent = 'Tenant / Courses active';
    wrap2.appendChild(label2);
    row.appendChild(wrap2);

    container.appendChild(row);
  },
};
