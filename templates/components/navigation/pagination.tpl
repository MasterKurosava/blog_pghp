<nav class="pagination{if $class} {$class|escape}{/if}" aria-label="{$ariaLabel|default:'Пагинация'|escape}">
    <a href="#" class="pagination__nav pagination__nav--prev pagination__nav--disabled" aria-label="Предыдущая страница">
        {include file="components/ui/icon.tpl" name="chevron-left" size="sm" ariaHidden=true}
    </a>

    <ol class="pagination__list">
        <li class="pagination__item">
            <a href="#" class="pagination__link pagination__link--active" aria-current="page">1</a>
        </li>
        <li class="pagination__item">
            <a href="#" class="pagination__link">2</a>
        </li>
        <li class="pagination__item">
            <a href="#" class="pagination__link">3</a>
        </li>
        <li class="pagination__item">
            <span class="pagination__ellipsis" aria-hidden="true">…</span>
        </li>
        <li class="pagination__item">
            <a href="#" class="pagination__link">12</a>
        </li>
    </ol>

    <a href="#" class="pagination__nav pagination__nav--next" aria-label="Следующая страница">
        {include file="components/ui/icon.tpl" name="chevron-right" size="sm" ariaHidden=true}
    </a>
</nav>
