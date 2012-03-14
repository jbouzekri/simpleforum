<?php

$Module = array( 'name' => 'Forum Topic Management',
                 'variable_params' => true );

$ViewList = array();

$ViewList['new'] = array(
    'script' => 'new.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ForumID' ) );

$ViewList['view'] = array(
    'script' => 'view.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID' ) );

$ViewList['list'] = array(
    'script' => 'list.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ForumID' ) );


?>
