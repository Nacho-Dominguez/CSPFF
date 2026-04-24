import { TenantAvatar, TenantAvatarMeta } from '../components/TenantAvatar/TenantAvatar.js';

export const TenantAvatarStory = {
  title: 'Tenant Avatar',
  section: 'Components',
  meta: TenantAvatarMeta,
  render(container) {
    // Row 1: sizes
    const sizeRow = document.createElement('div');
    sizeRow.className = 'story-row';

    const sizes = [
      { size: 'sm',      labelText: 'sm' },
      { size: 'default', labelText: 'default' },
      { size: 'lg',      labelText: 'lg' },
    ];

    sizes.forEach(({ size, labelText }) => {
      const item = document.createElement('div');
      item.className = 'story-item';
      const lbl = document.createElement('div');
      lbl.className = 'story-label';
      lbl.textContent = labelText;
      item.appendChild(lbl);
      item.appendChild(TenantAvatar({ initials: 'AC', size }));
      sizeRow.appendChild(item);
    });

    container.appendChild(sizeRow);

    // Row 2: custom bg colors
    const colorRow = document.createElement('div');
    colorRow.className = 'story-row';

    const colorVariants = [
      { labelText: 'blue',  bgColor: 'oklch(0.438 0.151 262)' },
      { labelText: 'green', bgColor: 'oklch(0.444 0.121 155)' },
      { labelText: 'amber', bgColor: 'oklch(0.520 0.140 63)'  },
    ];

    colorVariants.forEach(({ labelText, bgColor }) => {
      const item = document.createElement('div');
      item.className = 'story-item';
      const lbl = document.createElement('div');
      lbl.className = 'story-label';
      lbl.textContent = labelText;
      item.appendChild(lbl);
      item.appendChild(TenantAvatar({ initials: 'AC', size: 'default', bgColor }));
      colorRow.appendChild(item);
    });

    container.appendChild(colorRow);

    // Token reference
    const tokenRow = document.createElement('div');
    tokenRow.className = 'story-row';
    TenantAvatarMeta.tokens.forEach((token) => {
      const chip = document.createElement('span');
      chip.className = 'story-token';
      chip.textContent = token;
      tokenRow.appendChild(chip);
    });
    container.appendChild(tokenRow);
  },
};
