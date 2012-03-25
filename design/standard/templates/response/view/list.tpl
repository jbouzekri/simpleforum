<div id="response-{$response.id}" class="response">
    <h2>{$response.name}</h2>
    <div class="content">
        {$response.content}
    </div>
    <div class="rate">
        {if $response.total_vote|gt(0)}
            {ceil(mul(div($response.positive_vote,$response.total_vote),100))} %
        {else}
            {'Be the first to note this response'|i18n('simpleforum/response')}
        {/if}
        <p>
            {if $response.can_rate}
                {'Did you find this response useful ?'|i18n('simpleforum/response')}
                <a href="{concat('/response/rate/up/',$response.id)|ezurl('no')}">{'Yes'|i18n('simpleforum/response')}</a>
                <a href="{concat('/response/rate/down/',$response.id)|ezurl('no')}">{'No'|i18n('simpleforum/response')}</a>
            {else}
                {'You must be logged in to rate this response'|i18n('simpleforum/response')}
            {/if}
        </p>
    </div>
</div>