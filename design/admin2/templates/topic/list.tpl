{def $limit=ezini('NbElements','TopicListAdminNb','forum.ini.append.php')}
{if is_set( $view_parameters.offset )}
    {def $offset=$view_parameters.offset}
{else}
    {def $offset=0}
{/if}

{def $topics=fetch('topic', 'list', hash('forum_node_id', $forum.node_id,
                                         'limit', $limit,
                                         'offset', $offset ))}

{def $topics_count=fetch('topic', 'list_count', hash( 'forum_node_id', $forum.node_id ))}

<div class="box-header">
    <h1 class="context-title"><img width="32" height="32" title="General Forum" alt="General Forum" src="/share/icons/crystal/32x32/filesystems/folder_man.png" class="transparent-png-icon">&nbsp;{$forum.name|wash}&nbsp;[{$forum.object.class_name}]&nbsp;</h1>
    <div class="header-mainline"></div>
</div>

<div class="context-information">
    <p class="left modified">{'Last modified'|i18n('simpleforum')}: {$forum.published|l10n('shortdatetime')}, <a href="{$forum.object.owner.main_node.url_alias|ezurl('no')}">{$forum.object.owner.name|wash}</a> (Node ID: {$forum.node_id}, Object ID: {$forum.object.id})</p>
    <p class="right translation">English (United Kingdom)&nbsp;<img width="18" height="12" style="vertical-align: middle;" alt="" src="/share/icons/flags/eng-GB.gif"></p>
    <div class="break"></div>
</div>

<form action="/topic/action" method="post" name="topiclist">
    <div class="context-block">
        <div class="box-header">
            <div class="box-ml">
                <h1 class="context-title">{'Topic List'|i18n('smartphone/topic')}</h1>
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
                                <th width="45%">{'Subject'|i18n('smartphone/topic')}</th>
                                <th width="15%">{'Last Modified'|i18n('smartphone/topic')}</th>
                                <th width="5%">{'Nb views'|i18n('smartphone/topic')}</th>
                                <th width="5%">{'Nb responses'|i18n('smartphone/topic')}</th>
                                <th width="5%">{'State'|i18n('smartphone/topic')}</th>
                                <th width="20%">{'Actions'|i18n('smartphone/topic')}</th>
                            </tr>
                            {foreach $topics as $topic}
                                {topic_view_gui topic=$topic view='list'}
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/topic/list/',$forum.node_id)
         item_count=$topics_count
         view_parameters=$view_parameters
         item_limit=$limit}

{undef $topics $offset $topics_count $limit}