<form action="{concat('/topic/new/',$forum_id)|ezurl('no')}" method="post">

    <div class="controlbar" id="controlbar-top">
        <div class="box-bc"><div class="box-ml">
            <div class="button-left">
                <input type="submit" title="{'Create the new topic'|i18n('simpleforum/topic')}" value="{'Save topic'|i18n('simpleforum/topic')}" name="NewButton" class="defaultbutton">
                <input type="submit" title="{'Cancel action'|i18n('simpleforum/topic')}" onclick="return forumConfirmDiscard( '{'Are you sure you want to cancel?'|i18n('simpleforum/topic')}' );" value="{'Cancel Topic'|i18n('simpleforum/topic')}" name="CancelButton" class="button">
            </div>
            <div class="button-right"></div>
            <div class="float-break"></div>
        </div></div>
    </div>

    {if count($errors)}
        <div class="message-error">
            <h2><span class="time">[{currentdate()|l10n('shortdatetime')}]</span> {'The topic could not be saved.'|i18n('simpleforum/topic')}</h2>
            <p>{'Required data is either missing or is invalid:'|i18n('simpleforum/topic')}</p>
            <ul>
                {foreach $errors as $error}
                    <li>{$error}</li>
                {/foreach}
            </ul>
        </div>
    {/if}
    
    <div class="content-edit">
        <div class="context-block">
            
            <div class="box-header">
                <h1 class="context-title">{'Create new Topic'|i18n('simpleforum/topic')}</h1>
                <div class="header-mainline"></div>
            </div>

            <div class="box-content">
                <div class="context-information">
                    <p class="right translation">
                        English (United Kingdom)&nbsp;<img width="18" height="12" alt="eng-GB" style="vertical-align: middle;" src="/share/icons/flags/eng-GB.gif">
                    </p>
                    <div class="break"></div>
                </div>

                <div class="context-attributes">
                    
                    <div class="block ezcca-edit-datatype-ezstring simpleforum-topic-edit-name">
                        <label>{'Name'|i18n('simpleforum/topic')} <span class="required">({'required'|i18n('simpleforum/topic')})</span></label>
                        <input type="text" value="{$name}" name="name" size="70" class="box simpleforum-topic simpleforum-topic_name" id="simpleforum-topic_name" />
                    </div>
                    
                    <div class="block ezcca-edit-datatype-ezstring simpleforum-topic-edit-content">
                        <label>{'Content'|i18n('simpleforum/topic')} <span class="required">({'required'|i18n('simpleforum/topic')})</span></label>
                        <textarea name="content" size="70" class="box simpleforum-topic simpleforum-topic_content" id="simpleforum-topic_content">{$content}</textarea>
                    </div>
                    
                </div>
            </div>
            
            <div class="controlbar">
                <div class="block">
                    <input type="submit" title="{'Create the new topic'|i18n('simpleforum/topic')}" value="{'Save topic'|i18n('simpleforum/topic')}" name="NewButton" class="defaultbutton">
                    <input type="submit" title="{'Cancel action'|i18n('simpleforum/topic')}" onclick="return forumConfirmDiscard( '{'Are you sure you want to cancel?'|i18n('simpleforum/topic')}' );" value="{'Cancel Topic'|i18n('simpleforum/topic')}" name="CancelButton" class="button">
                </div>
            </div>
        </div>
    </div>
    
</form>
