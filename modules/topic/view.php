<?php

/**
 * @file
 * topic/view view
 * View the topic and its responses
 *
 * @author jobou
 * @package simpleforum
 */

$Module       = $Params['Module'];
$http         = eZHTTPTool::instance();
$cacheManager = simpleForumCacheManager::getezcManager();

$topicID = $Params['TopicID'];

$topic   = SimpleForumTopic::fetch($topicID);
if (!$topicID || !$topic)
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

// Test if user can read topic page
if (!$topic->canRead())
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

$offset = 0;
if ( isset($Params['Offset']) && $Params['Offset'] )
    $offset = (int) $Params['Offset'];

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
    $languageRedirect = "";
    if ($http->hasPostVariable('language_redirect'))
    {
        $languageRedirect = "/(language)/".$http->postVariable('language_redirect');
    }
    return $Module->redirectTo('/topic/list/'.$topic->attribute('node_id').$languageRedirect);
}
elseif ($http->hasPostVariable('DeleteButton') || $Module->isCurrentAction('Delete') )
{
    return $Module->redirectTo('/topic/delete/'.$topic->attribute('id'));
}

$tpl = eZTemplate::factory();

$tpl->setVariable('topic_id', $topicID);
$tpl->setVariable('topic',    $topic);

$tpl->setVariable('view_parameters', $viewParameters);

$cacheKey = 'topic_view_'.$topicID.'_'.$offset;
if (eZINI::instance()->variable('SimpleForumCacheSettings','CacheEnabled') == 'disabled')
{
    $resultContent = $tpl->fetch( 'design:topic/view.tpl' );
}
elseif ( ( $resultContent = $cacheManager->restore( $cacheKey, $topic->getCacheAttributes() ) ) === false )
{
    $resultContent = $tpl->fetch( 'design:topic/view.tpl' );
    $cacheManager->store( $cacheKey, $resultContent, $topic->getCacheAttributes() );
}
else
{
    eZDebug::writeDebug('View cache loaded');
}

$Result = array();
$Result['content'] = $resultContent;
$Result['path']    = $topic->fetchPath();

?>
