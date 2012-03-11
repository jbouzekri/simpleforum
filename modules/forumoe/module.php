<?php

$Module = array( 'name' => 'Forum Online Editor Popup',
                 'variable_params' => true );

$ViewList = array();

$ViewList['upload'] = array(
    'functions' => array( 'upload' ),
    'script'    => 'upload.php',
    'params'    => array( 'ObjectType', 'ObjectID', 'ContentType', 'ForcedUpload' ) );

$ViewList['embed'] = array(
    'functions' => array( 'upload' ),
    'script' => 'embed.php',
    'params' => array( 'ObjectType', 'ObjectID', 'FileID' )
    );

$FunctionList = array();
$FunctionList['upload'] = array();

?>
