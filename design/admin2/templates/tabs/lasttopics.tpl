<div class="block">
    <label>{'Total'|i18n('simpleforum/topic')}:</label>
    <table class="list" cellspacing="0" summary="{'Topic stats per languages'|i18n( 'design/admin/node/view/full' )}">
        <tr>
            <th>{'Language'|i18n( 'design/admin/node/view/full' )}</th>
            <th>{'Locale'|i18n( 'design/admin/node/view/full' )}</th>
            <th>{'Total'|i18n('simpleforum/topic')}</th>
            <th>{'Published'|i18n('simpleforum/topic')}</th>
            <th>{'Validated'|i18n('simpleforum/topic')}</th>
            <th>{'Closed'|i18n('simpleforum/topic')}</th>
            <th>{'Moderated'|i18n('simpleforum/topic')}</th>
        </tr>
        {def $topic_count=false()}
        {def $topic_closed_count=false()}
        {def $topic_validated_count=false()}
        {def $topic_published_count=false()}
        {def $topic_moderated_count=false()}
        {foreach $node.object.languages as $language sequence array( bglight, bgdark ) as $style}
            {set $topic_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id, 'language', $language.locale))}
            {set $topic_closed_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id, 'attribute_filter', array('state','=','CLOSED'), 'language', $language.locale))}
            {set $topic_validated_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id, 'attribute_filter', array('state','=','VALIDATED'), 'language', $language.locale))}
            {set $topic_published_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id, 'attribute_filter', array('state','=','PUBLISHED'), 'language', $language.locale))}
            {set $topic_moderated_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id, 'attribute_filter', array('state','=','MODERATED'), 'language', $language.locale))}
            <tr class="{$style}">
                <td>
                    <img src="{$language.locale|flag_icon}" width="18" height="12" alt="{$language.locale}" />
                    &nbsp;
                    {if eq( $language.locale, $node.object.current_language )}
                        <b><a href={concat( '/topic/list/',$node.node_id,'/(language)/', $language.locale )|ezurl} title="{'Manage topics in language'|i18n( 'simpleforum/tabs' )}">{$language.name}</a></b>
                    {else}
                        <a href={concat( '/topic/list/',$node.node_id,'/(language)/', $language.locale )|ezurl} title="{'Manage topics in language'|i18n( 'simpleforum/tabs' )}">{$language.name}</a>
                    {/if}
                </td>
                <td>{$language.locale}</td>
                <td>{$topic_count}</td>
                <td>{$topic_published_count}</td>
                <td>{$topic_validated_count}</td>
                <td>{$topic_closed_count}</td>
                <td>{$topic_moderated_count}</td>
            </tr> 
        {/foreach}
    </table>
</div>

{def $topics=fetch('topic', 'list', hash('forum_node_id', $node.node_id,
                                         'sort_by', array('modified', 'desc'),
                                         'limit', ezini('NbElements','LastTopicNb','forum.ini.append.php')))}

<div class="block">
    <label>{'Last Modified Topics for current language'|i18n('simpleforum/topic')}:</label>
    <table cellspacing="0" summary="Last topics" class="list">
        <tbody>
            <tr>
                <th>{'Subject'|i18n('simpleforum/topic')}</th>
                <th>{'Author'|i18n('simpleforum/topic')}</th>
                <th>{'Last Modified'|i18n('simpleforum/topic')}</th>
                <th class="tight">{'State'|i18n('simpleforum/topic')}</th>
                <th class="tight">{'Nb views'|i18n('simpleforum/topic')}</th>
                <th class="tight">{'Nb responses'|i18n('simpleforum/topic')}</th>
            </tr>
            {if count($topics)}
                {foreach $topics as $topic  sequence array( bglight, bgdark ) as $class}
                    {topic_view_gui topic=$topic view='line' class=$class}
                {/foreach}
            {else}
                <tr>
                    <td colspan="6">{'No topic'|i18n('simpleforum/topic')}</td>
                </tr>
            {/if}
        </tbody>
    </table>
</div>

<a href="{concat('/topic/list/',$node.node_id,'/(language)/',$node.object.current_language)|ezurl('no')}">{'Manage topics'|i18n('simpleforum/topic')}</a>

{undef $topics}
{undef $topic_count $topic_closed_count $topic_validated_count $topic_published_count $topic_moderated_count}