<h1>{$topic.name}</h1>

{$topic.content}

<a class="new-response" href="{concat('/response/new/',$topic.id)|ezurl('no')}">{'New response'|i18n('simpleforum/response')}</a>


{def $responses=fetch('response','list',hash('topic_id',$topic.id))}
<ul>
    {foreach $responses as $response}
        {response_view_gui response=$response view='list'}
    {/foreach}
</ul>

<a href="{$topic.forum_node.url_alias|ezurl('no')}">{'Back to forum'|i18n('simpleforum/topic')}</a>
