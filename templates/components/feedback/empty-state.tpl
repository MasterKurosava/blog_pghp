{assign var="_description" value=$description|default:$text|default:''}
{assign var="_buttonLabel" value=$button|default:$actionLabel|default:''}
{assign var="_buttonUrl" value=$buttonUrl|default:$actionHref|default:''}

<div class="empty-state{if $class|default:''} {$class|escape}{/if}" role="status" aria-live="polite">
    <div class="empty-state__visual">
        <span class="empty-state__ring" aria-hidden="true"></span>
        <div class="empty-state__icon" aria-hidden="true">
            {include file="components/ui/icon.tpl" name=$icon|default:'file' size="lg" ariaHidden=true}
        </div>
    </div>

    <h2 class="empty-state__title">{$title|default:'Статей пока нет'|escape}</h2>

    {if $_description}
        <p class="empty-state__description">{$_description|escape}</p>
    {/if}

    {if $_buttonLabel && $_buttonUrl}
        <div class="empty-state__actions">
            {include file="components/ui/button.tpl"
                variant=$buttonVariant|default:$actionVariant|default:'primary'
                size='md'
                href=$_buttonUrl
                label=$_buttonLabel
            }
        </div>
    {/if}
</div>
