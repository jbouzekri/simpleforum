<?php

$Module = array( 'name' => 'Forum Topic Management',
                 'variable_params' => true );

$ViewList = array();

$ViewList['new'] = array(
    'script' => 'new.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ForumID' ),
    'single_post_actions' => array( 'NewButton' => 'New',
                                    'CancelButton' => 'Cancel'));

$ViewList['view'] = array(
    'script' => 'view.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID' ),
    'unordered_params'=>array('offset'=>'Offset','sort'=>'Sort','order'=>'Order'),
    'single_post_actions' => array( 'NewResponseButton' => 'NewResponse',
                                    'BackToForumButton' => 'BackToForum',
                                    'BackToTopicListButton' => 'BackToTopicList',
                                    'DeleteTopicButton' => 'DeleteTopic') );

$ViewList['list'] = array(
    'script' => 'list.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ForumID' ),
    'unordered_params'=>array('offset'=>'Offset','sort'=>'Sort','order'=>'Order'),
    'single_post_actions' => array( 'NewButton' => 'New',
                                    'DeleteButton' => 'Delete',
                                    'BackToForumButton' => 'BackToForum'));

$ViewList['state'] = array(
    'script' => 'state.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID', 'NewState' ));

$ViewList['delete'] = array(
    'script' => 'delete.php',
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID' ));

?>
