import { SwitcherModal, SwitcherModalMeta } from '../components/SwitcherModal/SwitcherModal.js';
import { TenantAvatar } from '../components/TenantAvatar/TenantAvatar.js';

export const SwitcherModalStory = {
  title: 'SwitcherModal',
  section: 'Components',
  meta: SwitcherModalMeta,
  render(container) {
    const row = document.createElement('div');
    row.className = 'story-row';

    const wrap = document.createElement('div');
    wrap.className = 'story-item';

    const tenants = [
      { id: 't1', name: 'Acme Corp',         plan: 'Enterprise', avatar: TenantAvatar({ initials: 'AC' }) },
      { id: 't2', name: 'BlueLine Freight',   plan: 'Pro',        avatar: TenantAvatar({ initials: 'BF', bgColor: 'oklch(0.438 0.151 262)' }) },
      { id: 't3', name: 'Summit Logistics',   plan: 'Standard',   avatar: TenantAvatar({ initials: 'SL', bgColor: 'oklch(0.444 0.121 155)' }) },
      { id: 't4', name: 'Northwest Fleet',    plan: 'Trial',      avatar: TenantAvatar({ initials: 'NF', bgColor: 'oklch(0.520 0.140 63)'  }) },
    ];

    wrap.appendChild(SwitcherModal({ tenants, selectedId: 't1' }));

    const label = document.createElement('div');
    label.className = 'story-label';
    label.textContent = 'Switcher Modal — t1 selected';
    wrap.appendChild(label);

    row.appendChild(wrap);
    container.appendChild(row);
  },
};
