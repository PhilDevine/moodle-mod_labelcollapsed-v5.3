define('mod_labelcollapsed/toggler', [], function() {
    const init = () => {
        document.querySelectorAll('.modtype_labelcollapsed .lc_section').forEach(header => {
            if (header.dataset.initialized === 'true') return;
            header.dataset.initialized = 'true';

            const container = header.closest('.modtype_labelcollapsed');
            const targetSelector = header.getAttribute('data-target');
            const target = container.querySelector(targetSelector);
            if (!target) return;

            header.addEventListener('click', () => {
                const isShown = target.classList.contains('show');

                if (isShown) {
                    target.classList.remove('show');
                    target.style.maxHeight = null;
                    header.classList.remove('show');
                    header.setAttribute('aria-expanded', 'false');
                } else {
                    target.classList.add('show');
                    target.style.maxHeight = target.scrollHeight + 'px';
                    header.classList.add('show');
                    header.setAttribute('aria-expanded', 'true');
                }
            });
        });
    };
    return { init };
});
