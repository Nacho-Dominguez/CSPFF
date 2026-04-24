import { KPICard, KPICardMeta } from '../components/KPICard/KPICard.js';

export const KPICardStory = {
  title: 'KPI Card',
  section: 'Components',
  meta: KPICardMeta,
  render(container) {
    const row = document.createElement('div');
    row.className = 'story-row';

    // Positive trend
    const item1 = document.createElement('div');
    item1.className = 'story-item';
    const label1 = document.createElement('div');
    label1.className = 'story-label';
    label1.textContent = 'positive trend';
    item1.appendChild(label1);
    item1.appendChild(KPICard({ label: 'Active Drivers', value: '1,247', trend: '+12%', trendDirection: 'positive' }));
    row.appendChild(item1);

    // Negative trend
    const item2 = document.createElement('div');
    item2.className = 'story-item';
    const label2 = document.createElement('div');
    label2.className = 'story-label';
    label2.textContent = 'negative trend';
    item2.appendChild(label2);
    item2.appendChild(KPICard({ label: 'Incidents', value: '3', trend: '-2%', trendDirection: 'negative' }));
    row.appendChild(item2);

    // Neutral (no trend)
    const item3 = document.createElement('div');
    item3.className = 'story-item';
    const label3 = document.createElement('div');
    label3.className = 'story-label';
    label3.textContent = 'no trend';
    item3.appendChild(label3);
    item3.appendChild(KPICard({ label: 'Tenants', value: '24' }));
    row.appendChild(item3);

    container.appendChild(row);

    // Token reference
    const tokenRow = document.createElement('div');
    tokenRow.className = 'story-row';
    KPICardMeta.tokens.forEach((token) => {
      const chip = document.createElement('span');
      chip.className = 'story-token';
      chip.textContent = token;
      tokenRow.appendChild(chip);
    });
    container.appendChild(tokenRow);
  },
};
