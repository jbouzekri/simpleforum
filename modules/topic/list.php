<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$forumID = $Params['ForumID'];
$forum   = eZContentObjectTreeNode::fetch($forumID);
if ($forumID && (!$forum || $forum->classIdentifier() != 'forum'))
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$tpl = eZTemplate::factory();

$tpl->setVariable('forum_id', $forumID);
$tpl->setVariable('forum',    $forum);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:topic/list.tpl' );
$Result['path'] = array( array( 'url' => 'topic/list',
                                'text' => 'List Topic' ) );

?>
