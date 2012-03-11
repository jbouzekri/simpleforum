<?php

include_once( 'kernel/common/template.php' );

$fileID         = isset( $Params['FileID'] ) ? (int) $Params['FileID'] : null;
$objectType     = isset( $Params['ObjectType'] ) ? $Params['ObjectType'] : null;
$objectID     = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : null;
$fileID         = isset( $Params['FileID'] ) ? (int) $Params['FileID'] : null;
$embedInline     = isset( $Params['EmbedInline'] ) ? $Params['EmbedInline'] === 'true' : false;
$embedSize       = isset( $Params['EmbedSize'] ) ? $Params['EmbedSize'] : '';
$embedObjectJSON = 'false';
$embedId         = 0;
$tagName         = $embedInline ? 'embed-inline' : 'embed';

$contentType   = 'auto';

if ( !$objectType || !$objectID )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'objectType/ObjectID' ) );
   eZExecution::cleanExit();
}

if ( !$fileID )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'FileID' ) );
   eZExecution::cleanExit();
}

$user = eZUser::currentUser();

$image     = SimpleForumImage::fetch( $fileID );
$imageIni  = eZINI::instance( 'image.ini' );
$params    = array('loadImages' => true, 'imagePreGenerateSizes' => array('small', 'original') );

if ( !$image )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ImageID', '%value' => $fileID ) );
   eZExecution::cleanExit();
}

$imageSizeArray  = $imageIni->variable( 'AliasSettings', 'ForumAliasList' );
$ini             = eZINI::instance( 'site.ini' );
$contentIni      = eZINI::instance( 'content.ini' );
$ezoeIni         = eZINI::instance( 'ezoe.ini' );
$upload          = new simpleForumUpload();
$classIdentifier = $upload->detectClassIdentifier( $image['mime'] );
$sizeTypeArray   = array();


if ( $contentType === 'auto' )
{
    // figgure out what content type group this class is in
    $contentType = simpleForumXMLInput::embedTagContentType( $classIdentifier );
}

if ( $embedSize && $contentType === 'images' )
{
    $params['imagePreGenerateSizes'][] = $embedSize;
}

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


// Get list of classes for embed and embed inline tags
// use specific class list this embed class type if it exists
if ( $contentIni->hasVariable( 'embed_' . $classIdentifier, 'AvailableClasses' ) )
    $classListData = $contentIni->variable( 'embed_' . $classIdentifier, 'AvailableClasses' );
else if ( $contentIni->hasVariable( 'embed-type_' . $contentType, 'AvailableClasses' ) )
    $classListData = $contentIni->variable( 'embed-type_' . $contentType, 'AvailableClasses' );
else if ( $contentIni->hasVariable( 'embed', 'AvailableClasses' ) )
    $classListData = $contentIni->variable( 'embed', 'AvailableClasses' );

// same for embed-inline
if ( $contentIni->hasVariable( 'embed-inline_' . $classIdentifier, 'AvailableClasses' ) )
    $classListInlineData = $contentIni->variable( 'embed-inline_' . $classIdentifier, 'AvailableClasses' );
else if ( $contentIni->hasVariable( 'embed-inline-type_' . $contentType, 'AvailableClasses' ) )
    $classListInlineData = $contentIni->variable( 'embed-inline-type_' . $contentType, 'AvailableClasses' );
else if ( $contentIni->hasVariable( 'embed-inline', 'AvailableClasses' ) )
    $classListInlineData = $contentIni->variable( 'embed-inline', 'AvailableClasses' );

// Get human readable class names
if ( $contentIni->hasVariable( 'embed', 'ClassDescription' ) )
    $classListDescription = $contentIni->variable( 'embed', 'ClassDescription' );
else
    $classListDescription = array();

if ( $contentIni->hasVariable( 'embed-inline', 'ClassDescription' ) )
    $classListDescriptionInline = $contentIni->variable( 'embed-inline', 'ClassDescription' );
else
    $classListDescriptionInline = array();

$classListInline = array();
if ( $classListInlineData )
{
    $classListInline['-0-'] = 'None';
    foreach ( $classListInlineData as $class )
    {
        if ( isset( $classListDescriptionInline[$class] ) )
            $classListInline[$class] = $classListDescriptionInline[$class];
        else
            $classListInline[$class] = $class;
    }
}

// attribute defaults
if ( $contentIni->hasVariable( 'embed', 'Defaults' ) )
    $attributeDefaults = $contentIni->variable( 'embed', 'Defaults' );
else
    $attributeDefaults = array();

if ( $contentIni->hasVariable( 'embed-inline', 'Defaults' ) )
    $attributeDefaultsInline = $contentIni->variable( 'embed-inline', 'Defaults' );
else
    $attributeDefaultsInline = array();


// view mode list
if ( $contentIni->hasVariable( 'embed_' . $classIdentifier, 'AvailableViewModes' ) )
    $viewList = array_unique( $contentIni->variable( 'embed_' . $classIdentifier, 'AvailableViewModes' ) );
elseif ( $contentIni->hasVariable( 'embed', 'AvailableViewModes' ) )
    $viewList = array_unique( $contentIni->variable( 'embed', 'AvailableViewModes' ) );
else
    $viewList = array();

if ( $contentIni->hasVariable( 'embed-inline_' . $classIdentifier, 'AvailableViewModes' ) )
    $viewListInline = array_unique( $contentIni->variable( 'embed-inline_' . $classIdentifier, 'AvailableViewModes' ) );
elseif ( $contentIni->hasVariable( 'embed-inline', 'AvailableViewModes' ) )
    $viewListInline = array_unique( $contentIni->variable( 'embed-inline', 'AvailableViewModes' ) );
else
    $viewListInline = array();

// custom attributes
$customAttributes = array( 'embed' => array(), 'embed-inline' => array() );

if ( $contentIni->hasVariable( 'embed_' . $classIdentifier, 'CustomAttributes' ) )
    $customAttributes['embed'] = $contentIni->variable( 'embed_' . $classIdentifier, 'CustomAttributes' );
else if ( $contentIni->hasVariable( 'embed-type_' . $contentType, 'CustomAttributes' ) )
    $customAttributes['embed'] = $contentIni->variable( 'embed-type_' . $contentType, 'CustomAttributes' );
else if ( $contentIni->hasVariable( 'embed', 'CustomAttributes' ) )
    $customAttributes['embed'] = $contentIni->variable( 'embed', 'CustomAttributes' );

if ( $contentIni->hasVariable( 'embed-inline_' . $classIdentifier, 'CustomAttributes' ) )
    $customAttributes['embed-inline'] = $contentIni->variable( 'embed-inline_' . $classIdentifier, 'CustomAttributes' );
else if ( $contentIni->hasVariable( 'embed-inline-type_' . $contentType, 'CustomAttributes' ) )
    $customAttributes['embed-inline'] = $contentIni->variable( 'embed-inline-type_' . $contentType, 'CustomAttributes' );
else if ( $contentIni->hasVariable( 'embed-inline', 'CustomAttributes' ) )
    $customAttributes['embed-inline'] = $contentIni->variable( 'embed-inline', 'CustomAttributes' );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'object', $image );
$tpl->setVariable( 'object_id', $image['id'] );
$tpl->setVariable( 'object_version', null );

$tpl->setVariable( 'url_object_type', $objectType );
$tpl->setVariable( 'url_object_id', $objectID );

/*
$tpl->setVariable( 'embed_id', $embedId );
$tpl->setVariable( 'embed_type', $embedType );
$tpl->setVariable( 'embed_object', $embedObject );
$tpl->setVariable( 'embed_data', ezjscAjaxContent::nodeEncode( $embedObject, $params ) );
$tpl->setVariable( 'content_type', $contentType );
$tpl->setVariable( 'content_type_name', ucfirst( rtrim( $contentType, 's' ) ) );
$tpl->setVariable( 'compatibility_mode', $ezoeIni->variable('EditorSettings', 'CompatibilityMode' ) );
*/
$tpl->setVariable( 'embed_id', $embedId );
$tpl->setVariable( 'embed_type', null );
$tpl->setVariable( 'embed_object', null );
$tpl->setVariable( 'embed_data', json_encode( $image ) );
$tpl->setVariable( 'content_type', $contentType );
$tpl->setVariable( 'content_type_name', ucfirst( rtrim( $contentType, 's' ) ) );
$tpl->setVariable( 'compatibility_mode', $ezoeIni->variable('EditorSettings', 'CompatibilityMode' ) );

$tpl->setVariable( 'tag_name', $tagName );

$xmlTagAliasList = $ezoeIni->variable( 'EditorSettings', 'XmlTagNameAlias' );
if ( isset( $xmlTagAliasList[$tagName] ) )
    $tpl->setVariable( 'tag_name_alias', $xmlTagAliasList[$tagName] );
else
    $tpl->setVariable( 'tag_name_alias', $tagName );

$classList = array();
$tpl->setVariable( 'view_list', json_encode( array( 'embed' => $viewList, 'embed-inline' => $viewListInline ) ) );
$tpl->setVariable( 'class_list', json_encode( array( 'embed' => $classList, 'embed-inline' => $classListInline ) ) );
$tpl->setVariable( 'attribute_defaults', json_encode( array( 'embed' => $attributeDefaults, 'embed-inline' => $attributeDefaultsInline ) ) );


$tpl->setVariable( 'custom_attributes', $customAttributes );
$tpl->setVariable( 'size_list', $sizeTypeArray );

$defaultSize = $contentIni->variable( 'ImageSettings', 'DefaultEmbedAlias' );
$tpl->setVariable( 'default_size', $defaultSize );

if ( $contentIni->hasVariable( 'ImageSettings', 'DefaultCropAlias' ) )
    $tpl->setVariable( 'default_crop_size', $contentIni->variable( 'ImageSettings', 'DefaultCropAlias' ) );
else
    $tpl->setVariable( 'default_crop_size', $defaultSize );

$tpl->setVariable( 'custom_attribute_style_map', json_encode( $ezoeIni->variable('EditorSettings', 'CustomAttributeStyleMap' ) ) );

$tpl->setVariable( 'persistent_variable', array() );

$tpl->setVariable( 'original_uri_string', eZURI::instance()->originalURIString() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:common/tag_embed_' . $contentType . '.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );

return $Result;


//eZExecution::cleanExit();
//$GLOBALS['show_page_layout'] = false;

?>