import './KPICard.css';

const TREND_COLOR_MAP = {
  positive: 'var(--component-kpi-card-trend-positive)',
  negative: 'var(--component-kpi-card-trend-negative)',
  neutral:  'var(--component-kpi-card-label-text)',
};

const TREND_PREFIX_MAP = {
  positive: '↑ ',
  negative: '↓ ',
  neutral:  '',
};

export function KPICard({ label = 'Metric', value = '0', trend = null, trendDirection = 'positive' } = {}) {
  const el = document.createElement('div');
  el.className = 'cspff-kpi';

  el.style.setProperty('--kpi-bg', 'var(--component-kpi-card-bg)');
  el.style.setProperty('--kpi-border', 'var(--component-kpi-card-border)');
  el.style.setProperty('--kpi-value-text', 'var(--component-kpi-card-value-text)');
  el.style.setProperty(
    '--kpi-trend-text',
    TREND_COLOR_MAP[trendDirection] ?? TREND_COLOR_MAP.neutral
  );

  const labelEl = document.createElement('div');
  labelEl.className = 'cspff-kpi__label';
  labelEl.textContent = label;
  el.appendChild(labelEl);

  const valueEl = document.createElement('div');
  valueEl.className = 'cspff-kpi__value';
  valueEl.textContent = value;
  el.appendChild(valueEl);

  if (trend !== null && trend !== undefined) {
    const trendEl = document.createElement('div');
    trendEl.className = 'cspff-kpi__trend';
    const prefix = TREND_PREFIX_MAP[trendDirection] ?? '';
    trendEl.textContent = prefix + trend;
    el.appendChild(trendEl);
  }

  return el;
}

export const KPICardMeta = {
  tokens: [
    '--component-kpi-card-bg',
    '--component-kpi-card-border',
    '--component-kpi-card-label-text',
    '--component-kpi-card-value-text',
    '--component-kpi-card-trend-positive',
    '--component-kpi-card-trend-negative',
  ],
};
