import { Button, ButtonMeta } from '../components/Button/Button.js';

export const ButtonStory = {
  title: 'Button',
  section: 'Components',
  meta: ButtonMeta,
  render(container) {
    const groups = [
      { variant: 'primary',   states: ['default', 'disabled', 'loading'] },
      { variant: 'ghost',     states: ['default', 'disabled'] },
      { variant: 'danger',    states: ['default', 'disabled'] },
      { variant: 'warn-ghost',states: ['default', 'disabled'] },
    ];

    groups.forEach(({ variant, states }) => {
      const section = document.createElement('div');
      section.className = 'story-section';

      const heading = document.createElement('div');
      heading.className = 'story-section-title';
      heading.textContent = variant;
      section.appendChild(heading);

      const row = document.createElement('div');
      row.className = 'story-row';

      states.forEach(state => {
        const wrap = document.createElement('div');
        wrap.className = 'story-item';

        wrap.appendChild(Button({
          label: `${variant} / ${state}`,
          variant,
          disabled: state === 'disabled',
          loading: state === 'loading',
        }));

        const tokenLabel = document.createElement('div');
        tokenLabel.className = 'story-token';
        tokenLabel.textContent = state === 'default'
          ? `--component-button-bg`
          : state === 'loading'
            ? 'loading state'
            : 'opacity: 0.45';
        wrap.appendChild(tokenLabel);

        row.appendChild(wrap);
      });

      section.appendChild(row);
      container.appendChild(section);
    });
  },
};
