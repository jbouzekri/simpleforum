{def $limit=ezini('NbElements','ResponseListAdminNb','forum.ini.append.php')}
{if is_set( $view_parameters.offset )}
    {def $offset=$view_parameters.offset}
{else}
    {def $offset=0}
{/if}

{def $responses=fetch('response', 'list', hash( 'topic_id', $topic_id,
                                                'limit', $limit,
                                                'offset', $offset,
                                                'sort_by', array('published', 'asc') ))}

{def $responses_count=fetch('response', 'list_count', hash( 'topic_id', $topic_id ))}

{if is_set( $notice )}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n('shortdatetime')}]</span> {$notice}</h2>
    </div>
{/if}

<div class="context-block">

    <div class="box-header">
        <h1 class="context-title">{$topic.name}</h1>
        <div class="header-mainline"></div>
    </div>

    <div class="context-information">
        <p class="left modified">{'Last modified'|i18n('simpleforum')}: {$topic.modified|l10n('shortdatetime')}, <a href="{$topic.user.contentobject.main_node.url_alias|ezurl('no')}">{$topic.user.contentobject.name|wash}</a> (Topic ID: {$topic_id}, Forum ID: {$topic.node_id})</p>
        <div class="break"></div>
    </div>
    
    <div class="simpleforum-topic_content">
        {$topic.content}
    </div>
    
    <form action="/topic/view/{$topic_id}" method="post" name="topiclist">
        
        <input type="submit" title="Create a new response" value="{'New Response'|i18n('simpleforum/topic')}" name="NewResponseButton" class="button" />
        
        <div class="context-block">
            <div class="box-bc">
                <div class="box-ml">
                    <div class="box-content simpleforum-topic_responses">
                        {if count($responses)}
                            {foreach $responses as $response}
                                {response_view_gui response=$response view='list'}
                            {/foreach}
                        {else}
                            {'No response yet'|i18n('simpleforum/topic')}
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        
        
        {include name=navigator
                uri='design:navigator/google.tpl'
                page_uri=concat('/topic/view/',$topic_id)
                item_count=$responses_count
                view_parameters=$view_parameters
                item_limit=$limit}

        <input type="submit" title="{'Return to forum page'|i18n('simpleforum/topic')}" value="{'Back to Forum'|i18n('simpleforum/topic')}" name="BackToForumButton" class="button" />
        <input type="submit" title="{'Return to topic list'|i18n('simpleforum/topic')}" value="{'Back to Topic List'|i18n('simpleforum/topic')}" name="BackToTopicListButton" class="button" />
    </form>

</div>

{undef $responses $offset $responses_count $limit}