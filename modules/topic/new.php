<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$forumID = $Params['ForumID'];
$forum   = eZContentObjectTreeNode::fetch($forumID);
if (!$forumID || !$forum || $forum->classIdentifier() != 'forum')
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$tpl = eZTemplate::factory();

$name    = "";
$content = "";
$errors  = array();
if( $http->hasPostVariable('create') )
{
    $name    = $http->postVariable('name');
    if (!$name || $name == "")
    {
        $errors[] = ezpI18n::tr( 'simpleforum/topic', 'Topic name is required' );
    }
    elseif (strlen($name) > 200)
    {
        $errors[] = ezpI18n::tr( 'simpleforum/topic', 'Topic name is too long (200 characters max)' );
    }
    
    $content = $http->postVariable('content');
    if (!$content || $content == "")
    {
        $errors[] = ezpI18n::tr( 'simpleforum/topic', 'Topic content is required' );
    }
    elseif (strlen($content) < 200)
    {
        $errors[] = ezpI18n::tr( 'simpleforum/topic', 'Topic content must be at least 200 characters long.' );
    }
}

$tpl->setVariable('forum_id', $forumID);
$tpl->setVariable('forum',    $forum);
$tpl->setVariable('name',     $name);
$tpl->setVariable('content',  $content);
$tpl->setVariable('errors',   $errors);

$tpl->setVariable('input_handler', new simpleForumXMLInput());

$Result = array();
$Result['content'] = $tpl->fetch( 'design:topic/new.tpl' );
$Result['path'] = array( array( 'url' => 'topic/new/'.$forumID,
                                'text' => 'Create New Topic' ) );
/*
$topic = SimpleForumTopic::create();
$topic->store();

var_dump($topic);
*/
?>
