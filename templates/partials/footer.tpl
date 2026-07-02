<footer class="footer" role="contentinfo">
    <div class="container footer__inner">
        <p class="footer__copy">
            &copy; {$smarty.now|date_format:"%Y"} {$app.name|escape}. {str key='ux.footer_rights'}
        </p>
        <p class="footer__note">{str key='ux.footer_note'}</p>
    </div>
</footer>
