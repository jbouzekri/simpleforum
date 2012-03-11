eZOEPopupUtils.selectByFileId = function(object_type, object_id, fileId) {
    // redirects to embed window of a specific file
    if ( fileId !== undefined )
    {
        var s = tinyMCEPopup.editor.settings;
        window.location = s.ez_extension_url + '/embed/' + object_type + '/' + object_id + '/' + fileId;
    }
};