import { Table, TableMeta } from '../components/Table/Table.js';

export const TableStory = {
  title: 'Table',
  section: 'Components',
  meta: TableMeta,
  render(container) {
    const item = document.createElement('div');
    item.className = 'story-item';

    const lbl = document.createElement('div');
    lbl.className = 'story-label';
    lbl.textContent = 'default';
    item.appendChild(lbl);

    item.appendChild(Table({
      columns: ['Tenant', 'Status', 'Plan', 'Drivers'],
      rows: [
        ['Acme Corp',         'Active', 'Enterprise', '142'],
        ['BlueLine Freight',  'Trial',  'Pro',         '38'],
        ['Summit Logistics',  'Paused', '—',            '0'],
        ['Northwest Fleet',   'Active', 'Standard',    '67'],
      ],
    }));

    container.appendChild(item);

    // Token reference
    const tokenRow = document.createElement('div');
    tokenRow.className = 'story-row';
    TableMeta.tokens.forEach((token) => {
      const chip = document.createElement('span');
      chip.className = 'story-token';
      chip.textContent = token;
      tokenRow.appendChild(chip);
    });
    container.appendChild(tokenRow);
  },
};
