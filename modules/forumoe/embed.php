<?php

include_once( 'kernel/common/template.php' );

$fileID     = isset( $Params['FileID'] ) ? (int) $Params['FileID'] : null;
$objectType = isset( $Params['ObjectType'] ) ? $Params['ObjectType'] : null;
$objectID   = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : null;

$image     = SimpleForumImage::fetch( $fileID );
$imageIni  = eZINI::instance( 'image.ini' );

if ( !$image )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ImageID', '%value' => $fileID ) );
   eZExecution::cleanExit();
}

$imageSizeArray  = $imageIni->variable( 'AliasSettings', 'ForumAliasList' );
$ini             = eZINI::instance( 'site.ini' );
$contentIni      = eZINI::instance( 'content.ini' );
$ezoeIni         = eZINI::instance( 'ezoe.ini' );
$sizeTypeArray   = array();

$upload          = new simpleForumUpload();
$classIdentifier = $upload->detectClassIdentifier( $image['mime'] );

$contentType = simpleForumXMLInput::embedTagContentType( $classIdentifier );

foreach( $imageSizeArray as $size )
{
    if ( $imageIni->hasVariable( $size, 'HideFromRelations' )
         && $imageIni->variable( $size, 'HideFromRelations' ) === 'enabled'  ) continue;
    if ( $imageIni->hasVariable( $size, 'GUIName' ) )
        $sizeTypeArray[$size] = $imageIni->variable( $size, 'GUIName' );
    else
        $sizeTypeArray[$size] = ucfirst( $size );
    $imagePixelSize = '';
    foreach( $imageIni->variable( $size, 'Filters' ) as $filter )
    {
        if ( strpos( $filter, 'geometry/scale' ) !== false or strpos( $filter, 'geometry/crop' ) !== false )
        {
            $filter = explode( '=', $filter );
            $filter = $filter[1];
            $filter = explode( ';', $filter );
            // Only support scale and crop that uses both width and height for now
            if ( isset( $filter[1] ) ) $imagePixelSize = $filter[0] . 'x' . $filter[1];
            else $imagePixelSize = '';
        }
    }
    $sizeTypeArray[$size] .= ' ' . $imagePixelSize;
}
$sizeTypeArray['original'] = 'Original';

$tpl = eZTemplate::factory();
$tpl->setVariable( 'object', $image );
$tpl->setVariable( 'embed_data', json_encode($image) );
$tpl->setVariable( 'url_object_type', $objectType );
$tpl->setVariable( 'url_object_id', $objectID );


$Result = array();
$Result['content'] = $tpl->fetch( 'design:common/tag_embed_' . $contentType . '.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );

return $Result;

?>
