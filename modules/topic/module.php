<?php

$Module = array( 'name' => 'Forum Topic Management',
                 'variable_params' => true );

$ViewList = array();
$ViewList['new'] = array(
    'script' => 'new.php',
    'params' => array( 'ForumID' ) );

$FunctionList = array();
$FunctionList['List'] = array( 'name' => 'list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'include_file' => 'extension/ourcustom/modules/ourcustom/ourcustomfunctioncollection.php',
                               'class' => 'OurCustomFunctionCollection',
                               'method' => 'fetchCustomFetch' ),
                               'parameter_type' => 'standard',
                               'parameters' => array(
                                   array( 'name'     => 'the',
                                          'type'     => 'integer',
                                          'required' => true,
                                          'default'  => 1
                                        ),
                                   array( 'name'     => 'params',
                                          'type'     => 'string',
                                          'required' => true,
                                          'default'  => ''
                                   )
                                )
);

?>
