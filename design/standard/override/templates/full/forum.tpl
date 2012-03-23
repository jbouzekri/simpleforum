{if is_set( $view_parameters.offset )}
    {def $offset=$view_parameters.offset}
{else}
    {def $offset=0}
{/if}

<h2>{'Forums'|i18n('simpleforum')}</h2>

{def $forums=fetch('content','list',hash('parent_node_id', $node.node_id))}
<table>
    <tr>
        <th>{'Forum Name'|i18n('simpleforum')}</th>
        <th>{'Topic Number'|i18n('simpleforum')}</th>
        <th>{'Last Modified'|i18n('simpleforum')}</th>
    </tr>
    {foreach $forums as $forum}
        {node_view_gui content_node=$forum view='list'}
    {/foreach}
</table>

<a href="{concat('/topic/new/',$node.node_id)|ezurl('no')}">{'Create a new topic'|i18n('simpleforum/topic')}</a>

<h2>{'Topics'|i18n('simpleforum')}</h2>

{def $topics=fetch('topic','list',hash(
    'forum_node_id', $node.node_id,
    'offset', $offset,
    'limit', ezini('NbElements', 'TopicListNb', 'forum.ini.append.php')
))}
{def $topics_count=fetch('topic','list_count',hash(
    'forum_node_id', $node.node_id
))}
<table>
    <tr>
        <th>{'Topic Name'|i18n('simpleforum')}</th>
        <th>{'Last Modified'|i18n('simpleforum')}</th>
        <th>{'View Number'|i18n('simpleforum')}</th>
        <th>{'Response Number'|i18n('simpleforum')}</th>
    </tr>
    {foreach $topics as $topic}
        {topic_view_gui topic=$topic view='list'}
    {/foreach}
</table>
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=$node.url_alias
         item_count=$topics_count
         view_parameters=$view_parameters
         item_limit=ezini('NbElements', 'TopicListNb', 'forum.ini.append.php')}

{undef}