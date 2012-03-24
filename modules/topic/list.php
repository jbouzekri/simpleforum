<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$offset  = $Params['Offset'];
$forumID = $Params['ForumID'];

$forum = eZContentObjectTreeNode::fetch($forumID);
if (!$forumID || !$forum || $forum->classIdentifier() != 'forum')
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

// Test if forum Node is Hidden
if (!$forum->canRead() || ($forum->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes()))
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

if (!SimpleForumTools::checkAccess($forum))
{
	return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

if ( $offset )
    $offset = (int) $offset;

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}

$viewParameters = array( 'offset' => $offset );
$viewParameters = array_merge( $viewParameters, $UserParameters );

$tpl = eZTemplate::factory();

if( $http->hasPostVariable('NewButton') || $Module->isCurrentAction('New') )
{
    return $Module->redirectTo('/topic/new/'.$forumID);
}
elseif( $http->hasPostVariable('DeleteButton') || $Module->isCurrentAction('Delete') )
{
    $deleteTopicIds = $http->postVariable('delete_ids');
    if (is_array($deleteTopicIds) && count($deleteTopicIds))
    {
        SimpleForumTopic::removeByIds($deleteTopicIds);
        $tpl->setVariable('notice', ezpI18n::tr( 'simpleforum/topic', 'Success when deleting topics' ) );
    }
}
elseif( $http->hasPostVariable('BackToForumButton') || $Module->isCurrentAction('BackToForum') )
{
    return $Module->redirectTo($forum->urlAlias());
}

$tpl->setVariable('forum_id', $forumID);
$tpl->setVariable('forum',    $forum);

$tpl->setVariable('view_parameters', $viewParameters);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:topic/list.tpl' );
$Result['path'] = array( array( 'url' => 'topic/list',
                                'text' => 'List Topic' ) );

?>
