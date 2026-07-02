document.addEventListener('DOMContentLoaded', () => {
    initRevealAnimations();
    initImagePlaceholders();
});

function initRevealAnimations() {
    const elements = document.querySelectorAll('.section--animate, .article-page > *');

    if (!('IntersectionObserver' in window)) {
        elements.forEach((element) => {
            element.classList.add('is-visible');
        });
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.08, rootMargin: '0px 0px -32px 0px' },
    );

    elements.forEach((element) => observer.observe(element));
}

function initImagePlaceholders() {
    const mediaElements = document.querySelectorAll('.article-card__media, .article-page__hero');

    mediaElements.forEach((media) => {
        const image = media.querySelector('img');

        if (!image) {
            media.classList.add('is-loaded');
            return;
        }

        media.setAttribute('aria-busy', 'true');

        const markLoaded = () => {
            media.classList.add('is-loaded');
            media.removeAttribute('aria-busy');
        };

        if (image.complete && image.naturalWidth > 0) {
            markLoaded();
            return;
        }

        image.addEventListener('load', markLoaded, { once: true });
        image.addEventListener('error', markLoaded, { once: true });
    });
}
