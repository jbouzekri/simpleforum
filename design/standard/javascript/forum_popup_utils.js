alert('load');

eZOEPopupUtils.selectByFilePath = function(filepath) {
    // redirects to embed window of a specific file
    if ( filepath !== undefined )
    {
        var s = tinyMCEPopup.editor.settings;
        window.location = s.ez_extension_url + '/relations/' + encodeURIComponent(filepath);
    }
};