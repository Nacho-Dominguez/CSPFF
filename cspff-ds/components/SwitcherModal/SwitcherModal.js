import './SwitcherModal.css';

/**
 * @param {{ tenants?: Array<{ id: string, name: string, plan: string, avatar: HTMLElement|null }>, selectedId?: string|null, onSelect?: ((id: string) => void)|null }} props
 */
export function SwitcherModal({ tenants = [], selectedId = null, onSelect = null } = {}) {
  const el = document.createElement('div');
  el.className = 'cspff-switcher';

  const header = document.createElement('div');
  header.className = 'cspff-switcher__header';
  header.textContent = 'Switch Tenant';
  el.appendChild(header);

  const list = document.createElement('ul');
  list.className = 'cspff-switcher__list';

  tenants.forEach(tenant => {
    const li = document.createElement('li');

    const btn = document.createElement('button');
    btn.className = 'cspff-switcher__item' + (tenant.id === selectedId ? ' cspff-switcher__item--selected' : '');

    if (tenant.avatar) {
      const avatarSlot = document.createElement('span');
      avatarSlot.appendChild(tenant.avatar);
      btn.appendChild(avatarSlot);
    }

    const textWrap = document.createElement('div');

    const name = document.createElement('div');
    name.className = 'cspff-switcher__item-name';
    name.textContent = tenant.name;
    textWrap.appendChild(name);

    const meta = document.createElement('div');
    meta.className = 'cspff-switcher__item-meta';
    meta.textContent = tenant.plan;
    textWrap.appendChild(meta);

    btn.appendChild(textWrap);

    btn.addEventListener('click', () => {
      if (onSelect) onSelect(tenant.id);
    });

    li.appendChild(btn);
    list.appendChild(li);
  });

  el.appendChild(list);
  return el;
}

export const SwitcherModalMeta = {
  tokens: [
    '--component-switcher-modal-bg',
    '--component-switcher-modal-border',
    '--component-switcher-modal-item-hover-bg',
    '--component-switcher-modal-item-selected-bg',
  ],
};
