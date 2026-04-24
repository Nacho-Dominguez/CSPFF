import './Table.css';

export function Table({ columns = [], rows = [] } = {}) {
  const wrap = document.createElement('div');
  wrap.className = 'cspff-table-wrap';

  const table = document.createElement('table');
  table.className = 'cspff-table';

  const thead = document.createElement('thead');
  const headerRow = document.createElement('tr');
  columns.forEach((col) => {
    const th = document.createElement('th');
    th.textContent = col;
    headerRow.appendChild(th);
  });
  thead.appendChild(headerRow);
  table.appendChild(thead);

  const tbody = document.createElement('tbody');
  rows.forEach((row) => {
    const tr = document.createElement('tr');
    row.forEach((cell) => {
      const td = document.createElement('td');
      td.textContent = cell;
      tr.appendChild(td);
    });
    tbody.appendChild(tr);
  });
  table.appendChild(tbody);

  wrap.appendChild(table);
  return wrap;
}

export const TableMeta = {
  tokens: [
    '--component-table-header-bg',
    '--component-table-row-bg',
    '--component-table-row-hover-bg',
    '--component-table-border',
  ],
};
