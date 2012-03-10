{if count($errors)}
    <ul class="errors">
        {foreach $errors as $error}
            <li>{$error}</li>
        {/foreach}
    </ul>
{/if}

<form action="{concat('/topic/new/',$forum_id)|ezurl('no')}" method="post">
    <input type="text" value="{$name}" name="name" />
    {include uri="design:common/forum_ezoe.tpl" input_handler=$input_handler textarea_name="content" ez_object=$forum}
    <input type="submit" name="create" value="{'Create'|i18n('simpleforum/topic')}" />
</form>