<tr class="{$class}">
    <td><a href="/topic/view/{$topic.id}">{$topic.name}</a></td>
    <td><a href="{$topic.user.contentobject.main_node.url_alias|ezurl('no')}">{$topic.user.contentobject.name}</a></td>
    <td>{$topic.modified|l10n('shortdatetime')}</td>
    <td>{$topic.state}</td>
    <td align="right" class="number">{$topic.view_count}</td>
    <td align="right" class="number">{$topic.response_count}</td>
</tr>