<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$forumID = $Params['ForumID'];
$forum   = eZContentObjectTreeNode::fetch($forumID);
if (!$forumID || !$forum || $forum->classIdentifier() != 'forum')
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

// Test if forum Node is Hidden
if (!$forum->canRead() || ($forum->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes()))
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

$tpl = eZTemplate::factory();

$name    = "";
$content = "";
$errors  = array();

if( $http->hasPostVariable('NewButton') || $Module->isCurrentAction('New') )
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
    
    if (!count($errors))
    {
        $newTopic = SimpleForumTopic::create(array(
            'name' => $name,
            'content' => $content,
            'node_id' => $forumID
        ));
        $newTopic->store();
        if ($newTopic->id)
        {
            eZContentCacheManager::clearContentCacheIfNeeded( $forum->object()->ID );
            return $Module->redirectTo('/topic/view/'.$newTopic->id);
        }
        else
        {
            $errors[] = ezpI18n::tr( 'simpleforum/topic', 'An error occured when trying to create the new topic' );
        }
    }
}
elseif ( $http->hasPostVariable('CancelButton') || $Module->isCurrentAction('Cancel') )
{
    return $Module->redirectTo('/topic/list/'.$forumID);
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

?>
