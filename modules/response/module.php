<?php

$Module = array( 'name' => 'Forum Topic Management',
                 'variable_params' => true );

$ViewList = array();
$ViewList['new'] = array(
    'script' => 'new.php',
    'functions' => array( 'create' ),
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID' ),
    'single_post_actions' => array( 'NewButton' => 'New',
                                    'CancelButton' => 'Cancel'));

$ViewList['state'] = array(
    'script' => 'state.php',
    'functions' => array( 'state' ),
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ResponseID', 'NewState' ));

$ViewList['delete'] = array(
    'script' => 'delete.php',
    'functions' => array( 'remove' ),
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ResponseID' ));

$Node = array(
    'name'=> 'Node',
    'values'=> array()
);

$Subtree = array(
    'name'=> 'Subtree',
    'values'=> array()
);

$FunctionList             = array();
$FunctionList[ 'create' ] = array('Node' => $Node, 'Subtree' => $Subtree);
$FunctionList[ 'state' ]  = array('Node' => $Node, 'Subtree' => $Subtree);
$FunctionList[ 'remove' ] = array('Node' => $Node, 'Subtree' => $Subtree);

?>
