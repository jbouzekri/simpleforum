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

<div class="responses">
    {def $responses=fetch('response','list',hash('topic_id',$topic.id))}
    {foreach $responses as $response}
        {response_view_gui response=$response view='list'}
    {/foreach}
</div>

<a href="{$topic.forum_node.url_alias|ezurl('no')}">{'Back to forum'|i18n('simpleforum/topic')}</a>

{ezscript_require( 'ezjsc::jquery' )}
<script type="text/javascript">
 var topic_view = {$topic.id};
</script>
