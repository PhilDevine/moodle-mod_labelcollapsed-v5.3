/* Keep the label badge independent of Moodle's inplace name editor. */
define('mod_labelcollapsed/editlink', [], function() {
    const init = (cmid, editurl, badgeText, badgeBackground, badgeColour) => {
        const selector = '#module-' + cmid + ', ' +
            '.modtype_labelcollapsed[data-id="' + cmid + '"]';

        const enhance = () => {
            const container = document.querySelector(selector);
            if (!container) {
                return;
            }
            const name = container.querySelector('.activityname .instancename') ||
                container.querySelector('.activityname') ||
                container.querySelector('.instancename');
            if (!name) {
                return;
            }
            // Moodle replaces the inplaceeditable content with an input on rename.
            // The badge must be OUTSIDE that component so it survives the change.
            const editable = name.closest('.inplaceeditable') ||
                name.querySelector('.inplaceeditable') ||
                name.closest('a') || name;
            const parent = editable.parentElement;
            if (!parent) {
                return;
            }
            parent.classList.add('labelcollapsed-edit-row');

            const link = editable.matches('a') ? editable : editable.querySelector('a');
            if (link && !link.closest('.inplaceeditable-form')) {
                if (link.getAttribute('href') !== editurl) {
                    link.href = editurl;
                }
                link.title = 'Edit collapsed label content';
                link.classList.add('labelcollapsed-editlink');
            }
            if (badgeText) {
                let badge = parent.querySelector(':scope > .labelcollapsed-edit-badge');
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'labelcollapsed-edit-badge';
                    badge.textContent = badgeText;
                    badge.style.backgroundColor = badgeBackground || '';
                    badge.style.color = badgeColour || '';
                    parent.insertBefore(badge, editable);
                }
            }
        };
        const watch = () => {
            const container = document.querySelector(selector);
            if (!container) {
                return;
            }
            enhance();
            const observer = new MutationObserver(enhance);
            observer.observe(container, {childList: true, subtree: true});
        };
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', watch, {once: true});
        } else {
            watch();
        }
    };
    return {init};
});
