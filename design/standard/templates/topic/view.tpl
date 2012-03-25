{if is_set( $view_parameters.offset )}
    {def $offset=$view_parameters.offset}
{else}
    {def $offset=0}
{/if}

<h1>{$topic.name}</h1>

<div class="content">
    {$topic.content}
</div>

<a class="new-response" href="{concat('/response/new/',$topic.id)|ezurl('no')}">{'New response'|i18n('simpleforum/response')}</a>

{def $responses=fetch('response','list',hash(
    'topic_id', $topic.id,
    'offset', $offset,
    'limit', ezini('NbElements', 'ResponseListNb', 'forum.ini.append.php')
))}
{def $responses_count=fetch('response','list_count',hash(
    'topic_id', $topic.id
))}
<div class="responses">
    {foreach $responses as $response}
        {response_view_gui response=$response view='list'}
    {/foreach}
</div>
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/topic/view/',$topic.id)|ezurl('no')
         item_count=$responses_count
         view_parameters=$view_parameters
         item_limit=ezini('NbElements', 'ResponseListNb', 'forum.ini.append.php')}

<a href="{$topic.forum_node.url_alias|ezurl('no')}">{'Back to forum'|i18n('simpleforum/topic')}</a>

{ezscript_require( 'ezjsc::jquery' )}
<script type="text/javascript">
 var topic_view    = {$topic.id};
 var response_rate = {$topic.id};
</script>
