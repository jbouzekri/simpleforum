{def $forums=fetch('content','list',hash('parent_node_id', $node.node_id))}
{foreach $forums as $forum}
    {node_view_gui content_node=$forum view='list'}
{/foreach}

{def $topics=fetch('topic','list',hash(
    'forum_node_id', $node.node_id
))}
{$topics|attribute(show)}


{undef}