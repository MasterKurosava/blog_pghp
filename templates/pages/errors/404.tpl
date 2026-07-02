<div class="error-page">
    <div class="error-page__decor" aria-hidden="true"></div>
    <div class="error-page__code" aria-hidden="true">404</div>

    <div class="error-page__content">
        {include file="components/feedback/empty-state.tpl"
            title=$heading|default:'Страница не найдена'
            description='Запрашиваемая страница не существует или была перемещена.'
            icon='search'
            button='Вернуться на главную'
            buttonUrl=$homeUrl
        }
    </div>
</div>
