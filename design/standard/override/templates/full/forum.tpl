{def $forums=fetch('content','list',hash('parent_node_id', $node.node_id))}
<ul>
    {foreach $forums as $forum}
        {node_view_gui content_node=$forum view='list'}
    {/foreach}
</ul>

{def $topics=fetch('topic','list',hash(
    'forum_node_id', $node.node_id
))}
<ul>
    {foreach $topics as $topic}
        {topic_view_gui topic=$topic view='list'}
    {/foreach}
</ul>

{undef}