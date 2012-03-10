<?php

$Module = array( 'name' => 'Forum Online Editor Popup',
                 'variable_params' => true );

$ViewList = array();

$ViewList['upload'] = array(
    'functions' => array( 'upload' ),
    'script'    => 'upload.php',
    'params'    => array( 'ObjectID', 'ObjectVersion', 'ContentType', 'ForcedUpload' ) );

$ViewList['relations'] = array(
    'functions' => array( 'upload' ),
    'script' => 'relations.php',
    'params' => array( 'ObjectID', 'ObjectVersion', 'ContentType', 'EmbedID', 'EmbedInline', 'EmbedSize' )
    );

$FunctionList = array();
$FunctionList['upload'] = array();

?>
