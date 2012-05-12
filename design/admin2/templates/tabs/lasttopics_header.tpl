{if $node.class_identifier|eq('forum')}
    <li id="node-tab-{$tab}" class="{if $last}last{else}middle{/if}{if $node_tab_index|eq( $tab )} selected{/if}">
        {def $topic_counts=fetch('topic','list_count', hash('forum_node_id',$node.node_id,'depth',$node.data_map.depth.content,'language',array()))}
        {if $tabs_disabled}
            <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{$tab_title|i18n('simpleforum/tabs')} ({$topic_counts})</span>
        {else}
            <a href={concat( $node_url_alias, '/(tab)/', $tab )|ezurl} title="{$tab_description|i18n('simpleforum/tabs')}">{$tab_title|i18n('simpleforum/tabs')} ({$topic_counts})</a>
        {/if}
        {undef $topic_counts}
    </li>
{/if}