(() => {
    const carousel = document.querySelector('[data-carousel]');
    if (carousel) {
        const slides = [...carousel.querySelectorAll('[data-slide]')];
        const dots = [...carousel.querySelectorAll('[data-carousel-dot]')];
        let current = Math.max(0, slides.findIndex((slide) => slide.classList.contains('is-active')));

        const showSlide = (index) => {
            current = (index + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => {
                const active = slideIndex === current;
                slide.classList.toggle('is-active', active);
                slide.setAttribute('aria-hidden', String(!active));
            });
            dots.forEach((dot, dotIndex) => {
                dot.setAttribute('aria-current', String(dotIndex === current));
            });
        };

        carousel.querySelector('[data-carousel-prev]')?.addEventListener('click', () => showSlide(current - 1));
        carousel.querySelector('[data-carousel-next]')?.addEventListener('click', () => showSlide(current + 1));
        dots.forEach((dot, index) => dot.addEventListener('click', () => showSlide(index)));

        if (slides.length > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            let timer = window.setInterval(() => {
                if (!document.hidden) showSlide(current + 1);
            }, 6500);
            carousel.addEventListener('mouseenter', () => window.clearInterval(timer), { once: true });
        }
    }

    document.querySelectorAll('[data-filter-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const panel = document.querySelector(button.getAttribute('aria-controls'));
            if (!panel) return;
            const expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', String(!expanded));
            panel.classList.toggle('is-open', !expanded);
        });
    });

    document.querySelectorAll('[data-sort-select]').forEach((select) => {
        select.addEventListener('change', () => select.form?.requestSubmit());
    });

    document.querySelectorAll('[data-pincode-check]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const input = form.querySelector('input');
            const message = form.querySelector('[data-pincode-message]');
            if (!input || !message) return;
            if (!/^[1-9][0-9]{5}$/.test(input.value.trim())) {
                message.textContent = 'Enter a valid 6-digit Indian pincode.';
                input.focus();
                return;
            }
            message.textContent = 'Live pincode service is not configured. Confirm delivery at checkout.';
        });
    });

    document.querySelectorAll('#header-pincode').forEach((input) => {
        input.addEventListener('input', () => {
            const message = input.closest('.location-popover')?.querySelector('[data-pincode-message]');
            if (!message) return;
            if (input.value.length === 6 && /^[1-9][0-9]{5}$/.test(input.value)) {
                message.textContent = 'Live pincode service is not configured yet.';
            } else {
                message.textContent = 'Enter a 6-digit pincode. Live delivery lookup is not configured.';
            }
        });
    });

    document.querySelectorAll('[data-gallery-thumb]').forEach((button) => {
        button.addEventListener('click', () => {
            const main = document.querySelector('[data-gallery-main]');
            const art = main?.querySelector('.product-art');
            if (!art) return;
            const style = button.dataset.galleryThumb;
            const allowed = ['tablet', 'capsule', 'bottle', 'tube', 'vial', 'supplement', 'jar', 'device'];
            if (!allowed.includes(style)) return;
            art.className = `product-art product-art--${style} product-art--detail`;
            document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
                thumb.classList.toggle('is-active', thumb === button);
                thumb.setAttribute('aria-pressed', String(thumb === button));
            });
        });
    });

    document.querySelectorAll('[data-search-suggestions]').forEach((input) => {
        const list = document.getElementById(input.getAttribute('list') || '');
        if (!list) return;
        const feedback = document.getElementById('search-feedback');
        let timer;
        input.addEventListener('input', () => {
            window.clearTimeout(timer);
            const term = input.value.trim();
            if (term.length < 2) {
                list.replaceChildren();
                return;
            }
            timer = window.setTimeout(async () => {
                try {
                    const url = new URL(input.dataset.searchSuggestions, window.location.origin);
                    url.searchParams.set('q', term);
                    const response = await fetch(url, {
                        headers: { Accept: 'application/json' },
                        credentials: 'same-origin',
                    });
                    if (!response.ok) {
                        if (feedback) feedback.textContent = 'Search suggestions are temporarily unavailable.';
                        return;
                    }
                    const suggestions = await response.json();
                    list.replaceChildren(...suggestions.map((suggestion) => {
                        const option = document.createElement('option');
                        option.value = suggestion.name;
                        option.label = `${suggestion.name} · ${suggestion.category}`;
                        return option;
                    }));
                    if (feedback) feedback.textContent = suggestions.length ? `${suggestions.length} suggestions available.` : 'No suggestions found.';
                } catch (error) {
                    list.replaceChildren();
                    if (feedback) feedback.textContent = 'Search suggestions are temporarily unavailable.';
                    console.error('Product search suggestions failed.', error);
                }
            }, 180);
        });
    });
})();
