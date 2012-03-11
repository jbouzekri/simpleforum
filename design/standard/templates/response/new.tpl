{if count($errors)}
    <ul class="errors">
        {foreach $errors as $error}
            <li>{$error}</li>
        {/foreach}
    </ul>
{/if}

<form action="{concat('/response/new/',$topic_id)|ezurl('no')}" method="post">
    <input type="text" value="{$name}" name="name" />
    <textarea name="content">{$content}</textarea>
    <input type="submit" name="create" value="{'Create'|i18n('simpleforum/response')}" />
</form>