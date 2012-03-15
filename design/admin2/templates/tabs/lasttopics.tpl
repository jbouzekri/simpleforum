{def $topic_count=fetch('topic', 'list_count', hash('forum_node_id', $node.node_id))}
{'Total'|i18n('simpleforum/topic')} : {$topic_count}

{def $topics=fetch('topic', 'list', hash('forum_node_id', $node.node_id,
                                         'sort_by', array('modified', 'desc'),
                                         'limit', ezini('NbElements','LastTopicNb','forum.ini.append.php')))}
<ul>
    {foreach $topics as $topic}
        {topic_view_gui topic=$topic view='line'}
    {/foreach}
</ul>

<a href="{concat('/topic/list/',$node.node_id)|ezurl('no')}">{'Manage topics'|i18n('simpleforum/topic')}</a>

{undef $topics $topic_count}