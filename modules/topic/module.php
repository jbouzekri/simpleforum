<?php

/**
 * @file
 * Configure all view for topic module
 *
 * @author jobou
 * @package simpleforum
 */

$Module = array( 'name' => 'Forum Topic Management',
                 'variable_params' => true );

$ViewList = array();

$ViewList['new'] = array(
    'script' => 'new.php',
    'functions' => array( 'create' ),
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ForumID' ),
    'unordered_params'=>array('language'=>'Language'),
    'single_post_actions' => array( 'NewButton' => 'New',
                                    'CancelButton' => 'Cancel'));

$ViewList['view'] = array(
    'script' => 'view.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID' ),
    'unordered_params'=>array('offset'=>'Offset','sort'=>'Sort','order'=>'Order'),
    'single_post_actions' => array( 'NewResponseButton' => 'NewResponse',
                                    'BackToForumButton' => 'BackToForum',
                                    'BackToTopicListButton' => 'BackToTopicList',
                                    'DeleteTopicButton' => 'DeleteTopic') );

$ViewList['list'] = array(
    'script' => 'list.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'ForumID' ),
    'unordered_params'=>array('offset'=>'Offset','sort'=>'Sort','order'=>'Order','language'=>'Language'),
    'single_post_actions' => array( 'NewButton' => 'New',
                                    'DeleteButton' => 'Delete',
                                    'BackToForumButton' => 'BackToForum'));

$ViewList['state'] = array(
    'script' => 'state.php',
    'functions' => array( 'state' ),
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID', 'NewState' ));

$ViewList['delete'] = array(
    'script' => 'delete.php',
    'functions' => array( 'remove' ),
    'default_navigation_part' => 'ezforumnavigationpart',
    'params' => array( 'TopicID' ));

$ViewList['inc_view_count'] = array(
		'script' => 'inc_view_count.php',
		'functions' => array( 'read' ),
		'default_navigation_part' => 'ezforumnavigationpart',
		'params' => array( 'TopicID' ));

$ViewList['search'] = array(
        'script' => 'search.php',
        'functions' => array( 'read' ),
        'default_navigation_part' => 'ezforumnavigationpart',
        'unordered_params'=>array('offset'=>'Offset'),
        'single_post_actions' => array( 'SearchButton' => 'Search' ) );

$Node = array(
    'name'=> 'Node',
    'values'=> array()
);

$Subtree = array(
    'name'=> 'Subtree',
    'values'=> array()
);

$FunctionList             = array();
$FunctionList[ 'read' ]   = array('Node' => $Node, 'Subtree' => $Subtree);
$FunctionList[ 'create' ] = array('Node' => $Node, 'Subtree' => $Subtree);
$FunctionList[ 'state' ]  = array('Node' => $Node, 'Subtree' => $Subtree);
$FunctionList[ 'remove' ] = array('Node' => $Node, 'Subtree' => $Subtree);

?>
