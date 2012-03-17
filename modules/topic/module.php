<?php

$Module = array( 'name' => 'Forum Topic Management',
                 'variable_params' => true );

$ViewList = array();

$ViewList['new'] = array(
    'script' => 'new.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ForumID' ),
    'single_post_actions' => array( 'NewButton' => 'New',
                                    'CancelButton' => 'Cancel'),
    'post_action_parameters' => array( 'New' => array(  )));

$ViewList['view'] = array(
    'script' => 'view.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID' ) );

$ViewList['list'] = array(
    'script' => 'list.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ForumID' ),
    'unordered_params'=>array('offset'=>'Offset','sort'=>'Sort','order'=>'Order'),
    'single_post_actions' => array( 'NewButton' => 'New',
                                    'DeleteButton' => 'Delete' ));

?>
