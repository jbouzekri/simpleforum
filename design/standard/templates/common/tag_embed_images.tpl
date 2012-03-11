{set scope=global persistent_variable=hash('title', 'Upload new Image'|i18n('design/standard/ezoe'),
                                           'scripts', array('ezoe/ez_core.js',
                                                            'ezoe/ez_core_animation.js',
                                                            'ezoe/ez_core_accordion.js',
                                                            'ezoe/popup_utils.js',
                                                            'forum_popup_utils.js'),
                                           'css', array()
                                           )}
<script type="text/javascript">
eZOEPopupUtils.embedObject = {$embed_data};

{literal}

tinyMCEPopup.onInit.add( eZOEPopupUtils.BIND( eZOEPopupUtils.init, window, {
    tagName: 'embed',
    form: 'EditForm',
    cancelButton: 'CancelButton',
    tagGenerator: function( tag, customTag )
    {
        return '<img id="__mce_tmp" src="JavaScript:void(0);" />';
    }
}));
    
{/literal}
</script>

<div class="upload-view">
    <form action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data">

        <div id="tabs" class="tabs">
            <ul>
                <li class="tab" title="{'Properties'|i18n('design/standard/ezoe/wai')}"><span><a href="JavaScript:void(0);">{'Upload'|i18n('design/admin/content/upload')}</a></span></li>
            </ul>
        </div>

        <div class="block"> 
            <div class="left">
                <input id="SaveButton" name="SaveButton" type="submit" value="{'OK'|i18n('design/standard/ezoe')}" />
                <input id="CancelButton" name="CancelButton" type="reset" value="{'Cancel'|i18n('design/standard/ezoe')}" />
            </div>
        </div>
                    
        <div class="panel_wrapper" style="min-height: 360px;">
            <img src="{$object.path|ezurl('no')}" />
        </div>
     </form>
</div>