<tr class="simpleforum-topic_item {$class}">
    <td><input type="checkbox" name="delete_ids[]" value="{$topic.id}" /></td>
    <td><a href="{$topic.url_alias|ezurl('no')}">{$topic.name|wash}</a></td>
    <td>{$topic.modified|l10n('shortdatetime')}</td>
    <td>{$topic.view_count}</td>
    <td>{$topic.response_count}</td>
    <td>{$topic.state}</td>
    <td class="actions">
        {if or($topic.is_moderated,$topic.is_closed)}
            <a href="{concat('/topic/state/',$topic.id,'/published')|ezurl('no')}"><img title="{'publish'|i18n('simpleforum/topic')}" alt="{'publish'|i18n('simpleforum/topic')}" width="16" height="16" src="{'icons/published.gif'|ezimage('no')}" /></a>
        {/if}
        {if or($topic.is_moderated,$topic.is_closed,$topic.is_published)}
            <a href="{concat('/topic/state/',$topic.id,'/validated')|ezurl('no')}"><img title="{'validate'|i18n('simpleforum/topic')}" alt="{'validate'|i18n('simpleforum/topic')}" width="16" height="16" src="{'icons/validate.png'|ezimage('no')}" /></a>
        {/if}
        {if or($topic.is_moderated,$topic.is_validated,$topic.is_published)}
            <a href="{concat('/topic/state/',$topic.id,'/closed')|ezurl('no')}"><img title="{'close'|i18n('simpleforum/topic')}" alt="{'close'|i18n('simpleforum/topic')}" width="16" height="16" src="{'icons/close.gif'|ezimage('no')}" /></a>
        {/if}
        {if or($topic.is_validated,$topic.is_published,$topic.is_closed)}
            <a href="{concat('/topic/state/',$topic.id,'/moderated')|ezurl('no')}"><img title="{'moderate'|i18n('simpleforum/topic')}" alt="{'moderate'|i18n('simpleforum/topic')}" width="16" height="16" src="{'icons/moderate.png'|ezimage('no')}" /></a>
        {/if}
    </td>
</tr>