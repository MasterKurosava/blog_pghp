<div class="error-page">
    <div class="error-page__decor" aria-hidden="true"></div>
    <div class="error-page__code" aria-hidden="true">500</div>

    <div class="error-page__content">
        {include file="components/feedback/empty-state.tpl"
            title=$heading|default:'Ошибка сервера'
            description=$message|default:'Произошла внутренняя ошибка. Попробуйте обновить страницу позже.'
            icon='file'
            button='Вернуться на главную'
            buttonUrl=$homeUrl
        }
    </div>
</div>
