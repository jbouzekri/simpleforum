{def $limit=ezini('NbElements','TopicListAdminNb','forum.ini.append.php')}
{if is_set( $view_parameters.offset )}
    {def $offset=$view_parameters.offset}
{else}
    {def $offset=0}
{/if}

{if is_set( $view_parameters.sort )}
    {def $sort=$view_parameters.sort}
{else}
    {def $sort='modified'}
{/if}

{if is_set( $view_parameters.order )}
    {def $order=$view_parameters.order}
{else}
    {def $order='desc'}
{/if}

{def $topics=fetch('topic', 'list', hash('forum_node_id', $forum.node_id,
                                         'limit', $limit,
                                         'offset', $offset,
                                         'sort_by', array($sort, $order) ))}

{def $topics_count=fetch('topic', 'list_count', hash( 'forum_node_id', $forum.node_id ))}

{if is_set( $notice )}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n('shortdatetime')}]</span> {$notice}</h2>
    </div>
{/if}

<div class="context-block">

    <div class="box-header">
        <h1 class="context-title"><img width="32" height="32" title="General Forum" alt="General Forum" src="/share/icons/crystal/32x32/filesystems/folder_man.png" class="transparent-png-icon">&nbsp;{$forum.name|wash}&nbsp;[{$forum.object.class_name}]&nbsp;</h1>
        <div class="header-mainline"></div>
    </div>

    <div class="context-information">
        <p class="left modified">{'Last modified'|i18n('simpleforum')}: {$forum.object.published|l10n('shortdatetime')}, <a href="{$forum.object.owner.main_node.url_alias|ezurl('no')}">{$forum.object.owner.name|wash}</a> (Node ID: {$forum.node_id}, Object ID: {$forum.object.id})</p>
        <p class="right translation">English (United Kingdom)&nbsp;<img width="18" height="12" style="vertical-align: middle;" alt="" src="/share/icons/flags/eng-GB.gif"></p>
        <div class="break"></div>
    </div>
        
    <form action="/topic/list/{$forum_id}" method="post" name="topiclist">
        
        <input type="submit" title="Create a new topic" value="{'New Topic'|i18n('simpleforum/topic')}" name="NewButton" class="button" />
        
        <div class="context-block">
            <div class="box-header">
                <div class="box-ml">
                    <h1 class="context-title">{'Topic List'|i18n('simpleforum/topic')}</h1>
                    <div class="header-mainline"></div>
                </div>
            </div>
            <div class="box-bc">
                <div class="box-ml">
                    <div class="box-content">
                        <table cellspacing="0" class="list cache">
                            <tbody>
                                <tr>
                                    <th width="5%"><input type="checkbox" /></th>
                                    <th width="45%"><a href="{concat('/topic/list/',$forum_id,'/(sort)/name')|ezurl('no')}/(order)/{if $order|eq('asc')}desc{else}asc{/if}">{'Subject'|i18n('simpleforum/topic')}</a></th>
                                    <th width="15%"><a href="{concat('/topic/list/',$forum_id,'/(sort)/modified')|ezurl('no')}/(order)/{if $order|eq('asc')}desc{else}asc{/if}">{'Last Modified'|i18n('simpleforum/topic')}</a></th>
                                    <th width="5%"><a href="{concat('/topic/list/',$forum_id,'/(sort)/view_count')|ezurl('no')}/(order)/{if $order|eq('asc')}desc{else}asc{/if}">{'Nb views'|i18n('simpleforum/topic')}</a></th>
                                    <th width="5%"><a href="{concat('/topic/list/',$forum_id,'/(sort)/response_count')|ezurl('no')}/(order)/{if $order|eq('asc')}desc{else}asc{/if}">{'Nb responses'|i18n('simpleforum/topic')}</a></th>
                                    <th width="5%"><a href="{concat('/topic/list/',$forum_id,'/(sort)/state')|ezurl('no')}/(order)/{if $order|eq('asc')}desc{else}asc{/if}">{'State'|i18n('simpleforum/topic')}</a></th>
                                    <th width="20%">{'Actions'|i18n('simpleforum/topic')}</th>
                                </tr>
                                {if count($topics)}
                                    {foreach $topics as $topic}
                                        {topic_view_gui topic=$topic view='list'}
                                    {/foreach}
                                {else}
                                    <tr><td colspan="7">{'No topic'|i18n('simpleforum/topic')}</td></tr>
                                {/if}
                            </tbody>
                        </table>
                        <br />
                        <input type="submit" title="{'Delete Topics'|i18n('simpleforum/topic'}" value="{'Delete Topics'|i18n('simpleforum/topic')}" name="DeleteButton" class="button" />
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/topic/list/',$forum.node_id)
         item_count=$topics_count
         view_parameters=$view_parameters
         item_limit=$limit}

{undef $topics $offset $topics_count $limit $sort $order}