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

<div class="message-error" style="display:none;">
    <h2><span class="time">[{currentdate()|l10n('shortdatetime')}]</span> {'The state of the topic or the response could not be changed.'|i18n('simpleforum/topic')}</h2>
    <p>{'Contact an administrator'|i18n('simpleforum/topic')}</p>
</div>

<div class="context-block simpleforum_topic">

    <div class="box-header">
        <h1 class="context-title">{$topic.name}</h1>
        <div class="header-mainline"></div>
    </div>

    <div class="context-information">
        <p class="left modified">
            &nbsp;<img width="18" height="12" alt="{$topic.language_object.locale}" style="vertical-align: middle;" src="{$topic.language_object.locale|flag_icon}" />&nbsp;{$topic.language_object.name},&nbsp;
            {'Last modified'|i18n('simpleforum')}: {$topic.modified|l10n('shortdatetime')}, <a href="{$topic.user.contentobject.main_node.url_alias|ezurl('no')}">{$topic.user.contentobject.name|wash}</a> (Topic ID: {$topic_id}, Forum ID: {$topic.node_id}), {'State'|i18n('simpleforum/topic')}: {$topic.state|i18n('simpleforum/topic')}</p>
        <p class="right actions">
            {if or($topic.is_moderated,$topic.is_closed)}
                <a href="{concat('/topic/state/',$topic.id,'/published')|ezurl('no')}"><img title="{'publish'|i18n('simpleforum/topic')}" alt="{'publish'|i18n('simpleforum/topic')}" width="16" height="16" src="{'icons/published.gif'|ezimage('no')}" /></a>
            {/if}
            {if or($topic.is_moderated,$topic.is_closed,$topic.is_published)}
                <a href="{concat('/topic/state/',$topic.id,'/validated')|ezurl('no')}"><img title="{'validate'|i18n('simpleforum/topic')}" alt="{'validate'|i18n('simpleforum/topic')}" width="16" height="16" src="{'icons/validate.png'|ezimage('no')}" /></a>
            {/if}
            {if or($topic.is_moderated,$topic.is_validated,$topic.is_published)}
                <a href="{concat('/topic/state/',$topic.id,'/closed')|ezurl('no')}"><img title="{'close'|i18n('simpleforum/topic')}" alt="{'close'|i18n('simpleforum/topic')}" width="16" height="16" src="{'icons/close.gif'|ezimage('no')}" /></a>
            {/if}
            {if or($topic.is_validated,$topic.is_published,$topic.is_closed)}
                <a href="{concat('/topic/state/',$topic.id,'/moderated')|ezurl('no')}"><img title="{'moderate'|i18n('simpleforum/topic')}" alt="{'moderate'|i18n('simpleforum/topic')}" width="16" height="16" src="{'icons/moderate.png'|ezimage('no')}" /></a>
            {/if}
        </p>
        <div class="break"></div>
    </div>
    
    <div class="simpleforum-topic_content">
        {$topic.content}
    </div>
    
    <form action="/topic/view/{$topic_id}" method="post" name="topiclist">
        
        <input type="submit" title="Create a new response" value="{'New Response'|i18n('simpleforum/topic')}" name="NewResponseButton" class="defaultbutton" />
        <input type="submit" title="Delete topic" value="{'Delete Topic'|i18n('simpleforum/topic')}" onclick="return confirm( '{'Are you sure you want to delete the topic?'|i18n('simpleforum/topic')}' );" name="DeleteButton" class="button" />
        
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