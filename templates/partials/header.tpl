<header class="header">
    <div class="container header__inner">
        <a href="{url path='/'}" class="header__logo" aria-label="{str key='nav.logo_aria' name=$app.name|escape}">
            <span class="header__logo-mark" aria-hidden="true">B</span>
            <span class="header__logo-text">{$app.name|escape}</span>
        </a>

        <div class="header__search search" data-search data-debounce="{$app.ui.search_debounce_ms|default:400}">
            <label class="search__label sr-only" for="site-search">{str key='nav.search_aria'}</label>
            <span class="search__icon" aria-hidden="true">
                {include file="components/ui/icon.tpl" name="search" size="sm" ariaHidden=true}
            </span>
            <input
                id="site-search"
                type="search"
                class="search__input"
                placeholder="{str key='nav.search_placeholder'}"
                autocomplete="off"
                aria-controls="search-results"
                aria-expanded="false"
            >
            <button type="button" class="search__clear js-search-clear" hidden aria-label="{str key='nav.clear_search'}">
                {include file="components/ui/icon.tpl" name="close" size="sm" ariaHidden=true}
            </button>
            <div id="search-results" class="search__results" role="region" aria-label="{str key='search.results_aria'}" hidden>
                <div class="search__results-inner js-search-results"></div>
            </div>
        </div>

        <nav class="header__nav" aria-label="{str key='nav.main_aria'}">
            <a href="{url path='/'}" class="header__link">{str key='nav.home'}</a>
        </nav>
    </div>
</header>
