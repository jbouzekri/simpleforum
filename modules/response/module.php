<?php

$Module = array( 'name' => 'Forum Topic Management',
                 'variable_params' => true );

$ViewList = array();
$ViewList['new'] = array(
    'script' => 'new.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID' ),
    'single_post_actions' => array( 'NewButton' => 'New',
                                    'CancelButton' => 'Cancel'));

$ViewList['state'] = array(
    'script' => 'state.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ResponseID', 'NewState' ));

$ViewList['delete'] = array(
    'script' => 'delete.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ResponseID' ));

$FunctionList = array();

?>
