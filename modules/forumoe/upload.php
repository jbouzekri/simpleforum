<?php

$objectType     = isset( $Params['ObjectType'] ) ? $Params['ObjectType'] : 'forum';
$objectID       = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : 0;
$forcedUpload   = isset( $Params['ForcedUpload'] ) ? (int) $Params['ForcedUpload'] : 0;

$contentType   = 'objects';
if ( isset( $Params['ContentType'] ) && $Params['ContentType'] !== '' )
{
    $contentType   = $Params['ContentType'];
}

if ( $objectID === 0 )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ObjectID/ObjectType' ) );
   eZExecution::cleanExit();
}

/*$object    = eZContentObject::fetch( $objectID );
if ( !$object )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectId', '%value' => $objectID ) );
   eZExecution::cleanExit();
}*/

$http      = eZHTTPTool::instance();
$imageIni  = eZINI::instance( 'image.ini' );
$params    = array('dataMap' => array('image'));

if ( $http->hasPostVariable( 'uploadButton' ) || $forcedUpload )
{
    $upload = new simpleForumUpload();

    $objectName = '';
    if ( $http->hasPostVariable( 'objectName' ) )
    {
        $objectName = trim( $http->postVariable( 'objectName' ) );
    }

    $file = $upload->handleForumUpload( $result, 'fileName', $objectType.'/'.$objectID );
    if ( $file )
    {
        echo '<html><head><title>HiddenUploadFrame</title><script type="text/javascript">';
        echo 'window.parent.eZOEPopupUtils.selectByFileId( "'.$objectType.'", "'.$objectID.'", "' . $file->id . '" );';
        echo '</script></head><body></body></html>';
    }
    else
    {
        echo '<html><head><title>HiddenUploadFrame</title><script type="text/javascript">';
        echo 'window.parent.document.getElementById("upload_in_progress").style.display = "none";';
        echo '</script></head><body><div style="position:absolute; top: 0px; left: 0px;background-color: white; width: 100%;">';
        foreach( $result['errors'] as $err )
            echo '<p style="margin: 0; padding: 3px; color: red">' . $err['description'] . '</p>';
        echo '</div></body></html>';
    }
    eZDB::checkTransactionCounter();
    eZExecution::cleanExit();
}


$siteIni       = eZINI::instance( 'site.ini' );
$contentIni    = eZINI::instance( 'content.ini' );
$imageDatatypeArray = $siteIni->variable( 'ImageDataTypeSettings', 'AvailableImageDataTypes' );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'object_type', $objectType );
$tpl->setVariable( 'object_id', $objectID );
$tpl->setVariable( 'content_type', $contentType );

$contentTypeCase = ucfirst( $contentType );
if ( $contentIni->hasVariable( 'RelationGroupSettings', $contentTypeCase . 'ClassList' ) )
    $tpl->setVariable( 'class_filter_array', $contentIni->variable( 'RelationGroupSettings', $contentTypeCase . 'ClassList' ) );
else
    $tpl->setVariable( 'class_filter_array', array() );

$tpl->setVariable( 'content_type_name', rtrim( $contentTypeCase, 's' ) );

$tpl->setVariable( 'persistent_variable', array() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:common/upload_' . $contentType . '.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );

return $Result;

?>
