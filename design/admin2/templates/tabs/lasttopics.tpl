{def $topic_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id))}
{def $topic_closed_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id, 'attribute_filter', array('state','=','CLOSED')))}
{def $topic_validated_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id, 'attribute_filter', array('state','=','VALIDATED')))}
{def $topic_published_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id, 'attribute_filter', array('state','=','PUBLISHED')))}
{def $topic_moderated_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id, 'attribute_filter', array('state','=','MODERATED')))}

<div class="block">
    <label>{'Total'|i18n('simpleforum/topic')}:</label>
    <table cellspacing="0" summary="Total stats" class="list">
        <tbody>
            <tr>
                <th>{'Total'|i18n('simpleforum/topic')}</th>
                <th>{'Published'|i18n('simpleforum/topic')}</th>
                <th>{'Validated'|i18n('simpleforum/topic')}</th>
                <th>{'Closed'|i18n('simpleforum/topic')}</th>
                <th>{'Moderated'|i18n('simpleforum/topic')}</th>
            </tr>
            <tr>
                <td>{$topic_count}</td>
                <td>{$topic_published_count}</td>
                <td>{$topic_validated_count}</td>
                <td>{$topic_closed_count}</td>
                <td>{$topic_moderated_count}</td>
            </tr>
        </tbody>
    </table>
</div>

{def $topics=fetch('topic', 'list', hash('forum_node_id', $node.node_id,
                                         'sort_by', array('modified', 'desc'),
                                         'limit', ezini('NbElements','LastTopicNb','forum.ini.append.php')))}

<div class="block">
    <label>{'Last Modified Topics'|i18n('simpleforum/topic')}:</label>
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

<a href="{concat('/topic/list/',$node.node_id)|ezurl('no')}">{'Manage topics'|i18n('simpleforum/topic')}</a>

{undef $topics 
       $topic_count 
       $topic_closed_count
       $topic_published_count
       $topic_validated_count
       $topic_moderated_count}