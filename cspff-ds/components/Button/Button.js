import './Button.css';

/**
 * @param {{
 *   label?: string,
 *   variant?: 'primary'|'ghost'|'danger'|'warn-ghost',
 *   size?: 'default'|'sm',
 *   disabled?: boolean,
 *   loading?: boolean,
 *   iconLeft?: SVGElement|null,
 * }} props
 */
export function Button({
  label = 'Button',
  variant = 'primary',
  size = 'default',
  disabled = false,
  loading = false,
  iconLeft = null,
} = {}) {
  const el = document.createElement('button');
  el.className = `cspff-btn cspff-btn--${variant}${size === 'sm' ? ' cspff-btn--sm' : ''}${loading ? ' cspff-btn--loading' : ''}`;
  el.disabled = disabled;
  el.type = 'button';

  if (loading) {
    const spinner = document.createElement('span');
    spinner.className = 'cspff-btn__spinner';
    spinner.setAttribute('aria-hidden', 'true');
    el.appendChild(spinner);
  } else if (iconLeft) {
    iconLeft.className = 'cspff-btn__icon';
    el.appendChild(iconLeft);
  }

  el.appendChild(document.createTextNode(label));
  return el;
}

export const ButtonMeta = {
  tokens: [
    '--component-button-bg',
    '--component-button-bg-hover',
    '--component-button-bg-danger',
    '--component-button-bg-danger-hover',
    '--component-button-text',
    '--component-button-border',
    '--component-button-ghost-bg',
    '--component-button-ghost-text',
    '--component-button-ghost-hover',
  ],
};
