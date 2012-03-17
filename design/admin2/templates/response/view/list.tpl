<div class="simpleforum-response_item">
    <h2 class="simpleforum-response_name">{$response.name}</h2>
    <div class="context-information">
        <p class="left modified">{'Created At'|i18n('simpleforum')}: {$response.published|l10n('shortdatetime')}, <a href="{$response.user.contentobject.main_node.url_alias|ezurl('no')}">{$response.user.contentobject.name|wash}</a> (Response ID: {$response.id}), {'State'|i18n('simpleforum/response')}: {$response.state}</p>
        <p class="right actions">
            {if $response.is_moderated}
                <a href="{concat('/response/state/',$response.id,'/published')|ezul('no')}"><img title="{'publish'|i18n('simpleforum/response')}" alt="{'publish'|i18n('simpleforum/response')}" width="16" height="16" src="{'icons/published.gif'|ezimage('no')}" /></a>
            {/if}
            {if or($response.is_moderated,$response.is_published)}
                <a href="{concat('/response/state/',$response.id,'/validated')|ezul('no')}"><img title="{'validate'|i18n('simpleforum/response')}" alt="{'validate'|i18n('simpleforum/response')}" width="16" height="16" src="{'icons/validate.png'|ezimage('no')}" /></a>
            {/if}
            {if or($response.is_validated,$response.is_published)}
                <a href="{concat('/response/state/',$response.id,'/moderated')|ezul('no')}"><img title="{'moderate'|i18n('simpleforum/response')}" alt="{'moderate'|i18n('simpleforum/response')}" width="16" height="16" src="{'icons/moderate.png'|ezimage('no')}" /></a>
            {/if}
            <a href="{concat('/response/delete/',$response.id)|ezul('no')}" onclick="return confirm( '{'Are you sure you want to delete the response?'|i18n('simpleforum/response')}' );"><img title="{'delete'|i18n('simpleforum/response')}" alt="{'delete'|i18n('simpleforum/response')}" width="16" height="16" src="{'icons/delete.gif'|ezimage('no')}" /></a>
        </p>
        <div class="break"></div>
    </div>
    <div class="simpleforum-response_content">
        {$response.content}
    </div>
</div>