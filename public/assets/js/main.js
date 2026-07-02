document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.category-block, .home__empty');

    if (!('IntersectionObserver' in window)) {
        elements.forEach((el) => el.classList.add('fade-in'));
        return;
    }

    elements.forEach((el) => {
        el.classList.remove('fade-in');
        el.classList.add('fade-in--hidden');
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.remove('fade-in--hidden');
                entry.target.classList.add('fade-in--visible');
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -40px 0px' },
    );

    elements.forEach((el) => observer.observe(el));
});
