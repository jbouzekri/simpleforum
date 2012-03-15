<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$topicID = $Params['TopicID'];
$topic   = SimpleForumTopic::fetch($topicID);
if (!$topicID || !$topic)
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$tpl = eZTemplate::factory();

$tpl->setVariable('topic_id', $topicID);
$tpl->setVariable('topic',    $topic);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:topic/view.tpl' );
$Result['path'] = $topic->fetchPath();

?>
