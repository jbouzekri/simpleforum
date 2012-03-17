<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$offset  = $Params['Offset'];
$topicID = $Params['TopicID'];

$topic   = SimpleForumTopic::fetch($topicID);
if (!$topicID || !$topic)
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

if( $http->hasPostVariable('NewResponseButton') || $Module->isCurrentAction('NewResponse') )
{
    return $Module->redirectTo('/response/new/'.$topic->attribute('id'));
}
elseif ($http->hasPostVariable('BackToForumButton') || $Module->isCurrentAction('BackToForum') )
{
    return $Module->redirectTo($topic->forumNode()->urlAlias());
}
elseif ($http->hasPostVariable('BackToTopicListButton') || $Module->isCurrentAction('BackToTopicList') )
{
    return $Module->redirectTo('/topic/list/'.$topic->attribute('node_id'));
}

$tpl = eZTemplate::factory();

$tpl->setVariable('topic_id', $topicID);
$tpl->setVariable('topic',    $topic);

$tpl->setVariable('view_parameters', $viewParameters);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:topic/view.tpl' );
$Result['path'] = $topic->fetchPath();

?>
