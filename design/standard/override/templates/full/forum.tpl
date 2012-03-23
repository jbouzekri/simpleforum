{if is_set( $view_parameters.offset )}
    {def $offset=$view_parameters.offset}
{else}
    {def $offset=0}
{/if}

<h2>{$node.name|wash}</h2>

{def $forums=fetch('content','list',hash('parent_node_id', $node.node_id,'depth',1))}
{def $forums_count=fetch('content','list_count',hash('parent_node_id', $node.node_id,'depth',1))}
{if $forums_count}
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
{/if}

<h2>{'Topics'|i18n('simpleforum')}</h2>

<a href="{concat('/topic/new/',$node.node_id)|ezurl('no')}">{'Create a new topic'|i18n('simpleforum/topic')}</a>

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

{if $node.node_id|eq(ezini('NodeSettings', 'ForumRootNode', 'content.ini.append.php'))|not()}
    <a href="{$node.parent.url_alias|ezurl('no')}">{'Back to parent forum'|i18n('simpleforum')}</a>
{/if}
{undef}