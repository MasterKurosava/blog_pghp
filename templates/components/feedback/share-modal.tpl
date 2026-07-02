<div class="share-modal" id="share-modal" role="dialog" aria-modal="true" aria-labelledby="share-modal-title" hidden>
    <div class="share-modal__backdrop js-share-close" aria-hidden="true"></div>
    <div class="share-modal__panel">
        <header class="share-modal__header">
            <div>
                <h2 class="share-modal__title" id="share-modal-title">{str key='share.title'}</h2>
                <p class="share-modal__subtitle">{str key='share.subtitle'}</p>
            </div>
            <button type="button" class="share-modal__close js-share-close" aria-label="{str key='share.close'}">
                {include file="components/ui/icon.tpl" name="close" size="sm" ariaHidden=true}
            </button>
        </header>
        <p class="share-modal__article js-share-article-title"></p>
        <div class="share-modal__grid">
            <a href="#" class="share-modal__item js-share-link" data-network="telegram" target="_blank" rel="noopener noreferrer">
                <span class="share-modal__icon share-modal__icon--telegram" aria-hidden="true">TG</span>
                <span>{str key='share.telegram'}</span>
            </a>
            <a href="#" class="share-modal__item js-share-link" data-network="whatsapp" target="_blank" rel="noopener noreferrer">
                <span class="share-modal__icon share-modal__icon--whatsapp" aria-hidden="true">WA</span>
                <span>{str key='share.whatsapp'}</span>
            </a>
            <a href="#" class="share-modal__item js-share-link" data-network="twitter" target="_blank" rel="noopener noreferrer">
                <span class="share-modal__icon share-modal__icon--twitter" aria-hidden="true">X</span>
                <span>{str key='share.twitter'}</span>
            </a>
            <a href="#" class="share-modal__item js-share-link" data-network="facebook" target="_blank" rel="noopener noreferrer">
                <span class="share-modal__icon share-modal__icon--facebook" aria-hidden="true">f</span>
                <span>{str key='share.facebook'}</span>
            </a>
            <a href="#" class="share-modal__item js-share-link" data-network="linkedin" target="_blank" rel="noopener noreferrer">
                <span class="share-modal__icon share-modal__icon--linkedin" aria-hidden="true">in</span>
                <span>{str key='share.linkedin'}</span>
            </a>
            <a href="#" class="share-modal__item js-share-link" data-network="email">
                <span class="share-modal__icon share-modal__icon--email" aria-hidden="true">@</span>
                <span>{str key='share.email'}</span>
            </a>
            <button type="button" class="share-modal__item share-modal__item--copy js-share-copy">
                <span class="share-modal__icon share-modal__icon--copy" aria-hidden="true">⧉</span>
                <span>{str key='share.copy'}</span>
            </button>
        </div>
    </div>
</div>
