<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$offset  = $Params['Offset'];
$forumID = $Params['ForumID'];

$forum = eZContentObjectTreeNode::fetch($forumID);
if ($forumID && (!$forum || $forum->classIdentifier() != 'forum'))
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
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

$tpl->setVariable('forum_id', $forumID);
$tpl->setVariable('forum',    $forum);

$tpl->setVariable('view_parameters', $viewParameters);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:topic/list.tpl' );
$Result['path'] = array( array( 'url' => 'topic/list',
                                'text' => 'List Topic' ) );

?>
