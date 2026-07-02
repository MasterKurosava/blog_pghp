<article class="skeleton-card{if $class} {$class|escape}{/if}" aria-hidden="true" role="presentation">
    {include file="components/feedback/skeleton.tpl" variant='image'}

    <div class="skeleton-card__body">
        {include file="components/feedback/skeleton.tpl" variant='title'}
        {include file="components/feedback/skeleton.tpl" variant='text'}
        {include file="components/feedback/skeleton.tpl" variant='text' class='skeleton--short'}
        {include file="components/feedback/skeleton.tpl" variant='button'}
    </div>
</article>
