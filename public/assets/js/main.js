document.addEventListener('DOMContentLoaded', () => {
    const app = readAppConfig();
    initPageLoader();
    initPageSkeleton();
    initRevealAnimations();
    initImagePlaceholders();
    initSearch(app);
    initShareModal(app);
    initBackToTop();
    initInfiniteScroll(app);
    initPageTransitions();
});

function readAppConfig() {
    const body = document.body;

    return {
        searchUrl: body.dataset.apiSearch || '/api/search',
        searchMin: Number(body.dataset.searchMin || 2),
        searchEmptyTitle: body.dataset.searchEmptyTitle || 'Ничего не найдено',
        searchEmptyDescription: body.dataset.searchEmptyDescription || '',
        toastCopied: body.dataset.toastCopied || 'Ссылка скопирована',
        toastCopyError: body.dataset.toastCopyError || 'Ошибка копирования',
        toastSearchError: body.dataset.toastSearchError || 'Ошибка поиска',
        toastDuration: 3200,
    };
}

function initPageLoader() {
    const loader = document.getElementById('page-loader');
    if (!loader) return;

    loader.classList.add('is-active');
    window.addEventListener('load', () => {
        loader.classList.remove('is-active');
    }, { once: true });
}

function initPageSkeleton() {
    const skeleton = document.querySelector('.js-page-skeleton');
    const content = document.querySelector('.js-page-content');
    if (!skeleton || !content) return;

    skeleton.classList.add('is-visible');
    content.classList.add('is-loading');

    window.addEventListener('load', () => {
        skeleton.classList.remove('is-visible');
        content.classList.remove('is-loading');
    }, { once: true });
}

function initRevealAnimations() {
    const elements = document.querySelectorAll('.section--animate, .article-page > *');
    if (!('IntersectionObserver' in window)) {
        elements.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -32px 0px' });

    elements.forEach((el) => observer.observe(el));
}

function initImagePlaceholders() {
    document.querySelectorAll('.article-card__media, .article-page__hero').forEach((media) => {
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

function initSearch(app) {
    const root = document.querySelector('[data-search]');
    if (!root) return;

    const input = root.querySelector('.search__input');
    const clear = root.querySelector('.js-search-clear');
    const results = root.querySelector('.search__results');
    const resultsInner = root.querySelector('.js-search-results');
    const debounce = Number(root.dataset.debounce || 400);
    let timer = null;

    const toggleResults = (open) => {
        results.hidden = !open;
        input.setAttribute('aria-expanded', open ? 'true' : 'false');
    };

    const renderSkeleton = () => {
        resultsInner.innerHTML = `
            <div class="skeleton-card"><div class="skeleton skeleton--image"></div><div class="skeleton-card__body">
            <div class="skeleton skeleton--title"></div><div class="skeleton skeleton--text"></div></div></div>
            <div class="skeleton-card"><div class="skeleton skeleton--image"></div><div class="skeleton-card__body">
            <div class="skeleton skeleton--title"></div><div class="skeleton skeleton--text"></div></div></div>`;
        toggleResults(true);
    };

    const renderEmpty = () => {
        resultsInner.innerHTML = `
            <div class="search-empty">
                <strong>${escapeHtml(app.searchEmptyTitle)}</strong>
                <p>${escapeHtml(app.searchEmptyDescription)}</p>
            </div>`;
        toggleResults(true);
    };

    const renderResults = (articles) => {
        if (!articles.length) {
            renderEmpty();
            return;
        }

        resultsInner.innerHTML = articles.map((article) => `
            <a href="${escapeHtml(article.url)}" class="search-result">
                <span class="search-result__title">${escapeHtml(article.title)}</span>
                <span class="search-result__meta">${escapeHtml([article.category, article.reading_time, article.published_at].filter(Boolean).join(' · '))}</span>
            </a>
        `).join('');
        toggleResults(true);
        initImagePlaceholders();
    };

    const performSearch = async (query) => {
        if (query.length < app.searchMin) {
            toggleResults(false);
            resultsInner.innerHTML = '';
            return;
        }

        renderSkeleton();

        try {
            const response = await fetch(`${app.searchUrl}?q=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error('search failed');
            const data = await response.json();
            renderResults(data.articles || []);
        } catch {
            showToast(app.toastSearchError, 'error', app.toastDuration);
            toggleResults(false);
        }
    };

    input.addEventListener('input', () => {
        const value = input.value.trim();
        clear.hidden = value === '';
        clearTimeout(timer);
        timer = setTimeout(() => performSearch(value), debounce);
    });

    clear?.addEventListener('click', () => {
        input.value = '';
        clear.hidden = true;
        resultsInner.innerHTML = '';
        toggleResults(false);
        input.focus();
    });

    document.addEventListener('click', (event) => {
        if (!root.contains(event.target)) toggleResults(false);
    });
}

function initShareModal(app) {
    const modal = document.getElementById('share-modal');
    if (!modal) return;

    const titleEl = modal.querySelector('.js-share-article-title');
    const copyBtn = modal.querySelector('.js-share-copy');
    const links = modal.querySelectorAll('.js-share-link');
    let currentUrl = '';
    let currentTitle = '';

    const networks = {
        telegram: (url, title) => `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`,
        whatsapp: (url, title) => `https://wa.me/?text=${encodeURIComponent(`${title} ${url}`)}`,
        twitter: (url, title) => `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`,
        facebook: (url) => `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
        linkedin: (url, title) => `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`,
        email: (url, title) => `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(url)}`,
    };

    const openModal = (url, title) => {
        currentUrl = url;
        currentTitle = title;
        titleEl.textContent = title;
        links.forEach((link) => {
            const network = link.dataset.network;
            if (!network || !networks[network]) return;
            link.href = networks[network](url, title);
        });
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
        copyBtn.focus();
    };

    const closeModal = () => {
        modal.hidden = true;
        document.body.style.overflow = '';
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('.js-share-trigger');
        if (!trigger) return;
        event.preventDefault();
        openModal(trigger.dataset.shareUrl || '', trigger.dataset.shareTitle || document.title);
    });

    modal.querySelectorAll('.js-share-close').forEach((el) => {
        el.addEventListener('click', closeModal);
    });

    modal.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeModal();
    });

    copyBtn?.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(currentUrl);
            showToast(app.toastCopied, 'success', app.toastDuration);
            closeModal();
        } catch {
            showToast(app.toastCopyError, 'error', app.toastDuration);
        }
    });
}

function showToast(message, type = 'success', duration = 3200) {
    const stack = document.getElementById('toast-stack');
    if (!stack) return;

    const toast = document.createElement('div');
    toast.className = `toast toast--${type}`;
    toast.textContent = message;
    stack.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('is-leaving');
        setTimeout(() => toast.remove(), 200);
    }, duration);
}

function initBackToTop() {
    const button = document.querySelector('.js-back-to-top');
    if (!button) return;

    const toggle = () => {
        button.classList.toggle('is-visible', window.scrollY > 480);
        button.hidden = window.scrollY <= 480;
    };

    window.addEventListener('scroll', toggle, { passive: true });
    toggle();

    button.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

function initInfiniteScroll(app) {
    const page = document.querySelector('.category-page');
    if (!page) return;

    const apiBase = page.dataset.categoryApi;
    const sort = page.dataset.currentSort || 'newest';
    const grid = page.querySelector('.js-category-grid');
    const infiniteRoot = page.querySelector('.js-infinite-scroll');
    const sentinel = page.querySelector('.js-infinite-sentinel');
    const loader = page.querySelector('.js-infinite-loader');
    const endMessage = page.querySelector('.js-infinite-end');

    if (!apiBase || !grid || !infiniteRoot || !sentinel) return;

    let currentPage = Number(page.dataset.currentPage || 1);
    let lastPage = Number(page.dataset.lastPage || 1);
    let isLoading = false;

    const hasMore = () => currentPage < lastPage;

    const setLoaderVisible = (visible) => {
        if (!loader) return;
        loader.hidden = !visible;
    };

    const showEnd = () => {
        if (endMessage) endMessage.hidden = false;
        if (infiniteRoot) infiniteRoot.hidden = false;
        sentinel.style.display = 'none';
        setLoaderVisible(false);
    };

    const hideInfinite = () => {
        if (infiniteRoot) infiniteRoot.hidden = true;
    };

    if (!hasMore()) {
        if (lastPage > 1) {
            showEnd();
        } else {
            hideInfinite();
        }
        return;
    }

    const revealObserver = 'IntersectionObserver' in window
        ? new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -32px 0px' })
        : null;

    const animateNewItems = (fromIndex) => {
        const items = Array.from(grid.children).slice(fromIndex);
        items.forEach((item) => {
            item.classList.add('section--animate');
            if (revealObserver) {
                revealObserver.observe(item);
            } else {
                item.classList.add('is-visible');
            }
        });
    };

    const loadMore = async () => {
        if (isLoading || !hasMore()) return;

        isLoading = true;
        setLoaderVisible(true);

        const nextPage = currentPage + 1;
        const beforeCount = grid.children.length;

        try {
            const response = await fetch(`${apiBase}?page=${nextPage}&sort=${encodeURIComponent(sort)}`);
            if (!response.ok) throw new Error('load failed');

            const data = await response.json();
            if (data.html) {
                grid.insertAdjacentHTML('beforeend', data.html);
                animateNewItems(beforeCount);
                initImagePlaceholders();
            }

            currentPage = Number(data.page || nextPage);
            lastPage = Number(data.lastPage || lastPage);
            page.dataset.currentPage = String(currentPage);
            page.dataset.lastPage = String(lastPage);

            const url = new URL(window.location.href);
            url.searchParams.set('page', String(currentPage));
            window.history.replaceState({}, '', url.toString());

            if (!data.hasMore) {
                showEnd();
            }
        } catch {
            showToast(app.toastSearchError, 'error', app.toastDuration);
        } finally {
            isLoading = false;
            if (hasMore()) {
                setLoaderVisible(false);
            }
        }
    };

    if ('IntersectionObserver' in window) {
        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    loadMore();
                }
            });
        }, { rootMargin: '200px 0px', threshold: 0 });

        scrollObserver.observe(sentinel);
    } else {
        window.addEventListener('scroll', () => {
            const rect = sentinel.getBoundingClientRect();
            if (rect.top <= window.innerHeight + 200) {
                loadMore();
            }
        }, { passive: true });
    }
}

function initPageTransitions() {
    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');
        if (!link || link.target === '_blank' || link.origin !== window.location.origin) return;
        if (link.classList.contains('js-share-link') || link.closest('.search-result')) return;
        const loader = document.getElementById('page-loader');
        if (!loader) return;
        loader.classList.add('is-active');
    });
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}
