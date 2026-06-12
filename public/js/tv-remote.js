(function () {
    const focusableSelector = [
        'a[href]',
        'button:not([disabled])',
        'input:not([disabled]):not([type="hidden"])',
        'select:not([disabled])',
        'textarea:not([disabled])',
        'iframe[tabindex]',
        '[tabindex]:not([tabindex="-1"])',
    ].join(',');

    const arrowKeys = new Set(['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight']);

    function visibleFocusables() {
        return Array.from(document.querySelectorAll(focusableSelector)).filter((element) => {
            const rect = element.getBoundingClientRect();
            const style = window.getComputedStyle(element);

            return rect.width > 0
                && rect.height > 0
                && style.visibility !== 'hidden'
                && style.display !== 'none';
        });
    }

    function center(rect) {
        return {
            x: rect.left + rect.width / 2,
            y: rect.top + rect.height / 2,
        };
    }

    function candidateScore(current, candidate, direction) {
        const currentCenter = center(current.getBoundingClientRect());
        const candidateCenter = center(candidate.getBoundingClientRect());
        const dx = candidateCenter.x - currentCenter.x;
        const dy = candidateCenter.y - currentCenter.y;

        const isCandidate = {
            ArrowUp: dy < -8,
            ArrowDown: dy > 8,
            ArrowLeft: dx < -8,
            ArrowRight: dx > 8,
        }[direction];

        if (!isCandidate) {
            return Number.POSITIVE_INFINITY;
        }

        const primary = direction === 'ArrowUp' || direction === 'ArrowDown' ? Math.abs(dy) : Math.abs(dx);
        const secondary = direction === 'ArrowUp' || direction === 'ArrowDown' ? Math.abs(dx) : Math.abs(dy);

        return primary + secondary * 1.8;
    }

    function moveFocus(direction) {
        const focusables = visibleFocusables();

        if (focusables.length === 0) {
            return;
        }

        const current = document.activeElement && focusables.includes(document.activeElement)
            ? document.activeElement
            : focusables[0];

        const next = focusables
            .filter((element) => element !== current)
            .map((element) => ({
                element,
                score: candidateScore(current, element, direction),
            }))
            .filter((candidate) => Number.isFinite(candidate.score))
            .sort((a, b) => a.score - b.score)[0]?.element;

        (next || current).focus({ preventScroll: false });
    }

    function moveToNextInForm(current) {
        const focusables = visibleFocusables();
        const index = focusables.indexOf(current);
        const next = focusables[index + 1];

        if (next) {
            next.focus({ preventScroll: false });
        }
    }

    document.addEventListener('keydown', (event) => {
        const key = event.key || '';
        const active = document.activeElement;
        const isTextInput = active && ['INPUT', 'TEXTAREA'].includes(active.tagName);

        if (arrowKeys.has(key)) {
            if (isTextInput && (key === 'ArrowLeft' || key === 'ArrowRight')) {
                return;
            }

            event.preventDefault();
            moveFocus(key);
            return;
        }

        if (key === 'Enter' || key === 'NumpadEnter' || event.keyCode === 13) {
            if (isTextInput) {
                event.preventDefault();
                moveToNextInForm(active);
                return;
            }

            if (active && typeof active.click === 'function' && active.tagName !== 'IFRAME') {
                event.preventDefault();
                active.click();
            }
        }
    });

    window.addEventListener('load', () => {
        const focusables = visibleFocusables();

        if (!document.activeElement || document.activeElement === document.body) {
            focusables[0]?.focus({ preventScroll: true });
        }
    });
})();
