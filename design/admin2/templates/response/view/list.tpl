<div class="simpleforum-response">
    <h2 class="simpleforum-response_name">{$response.name}</h2>
    <div class="context-information">
        <p class="left modified">{'Created At'|i18n('simpleforum')}: {$response.published|l10n('shortdatetime')}, <a href="{$response.user.contentobject.main_node.url_alias|ezurl('no')}">{$response.user.contentobject.name|wash}</a> (Response ID: {$response.id})</p>
        <div class="break"></div>
    </div>
    <div class="simpleforum-response_content">
        {$response.content}
    </div>
</div>