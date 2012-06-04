<?php

/**
 * @file
 * topic/delete view
 * Delete a topic. Used in admin.
 *
 * @author jobou
 * @package simpleforum
 */

eZDebug::updateSettings(array(
		"debug-enabled" => false,
		"debug-by-ip" => false
));

$Module = $Params['Module'];
$http   = eZHTTPTool::instance();

$topicID  = $Params['TopicID'];

$topic = SimpleForumTopic::fetch($topicID);
if (!$topicID || !$topic)
{
    if ($http->variable('ajax'))
    {
         $Result = array();
         $Result['pagelayout'] = false;
         $Result['content']    = 1;
         return $Result;
    }
    else
    {
        return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
    }
}

// Test if user can read topic page
if (!$topic->canDelete())
{
    if ($http->variable('ajax'))
    {
         $Result = array();
         $Result['pagelayout'] = false;
         $Result['content']    = 2;
         return $Result;
    }
    else
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

$topic->remove();
$topic->decForumTopicCount();
eZContentCacheManager::clearContentCacheIfNeeded( $topic->forumNode()->object()->ID );
if (eZINI::instance()->variable('SimpleForumCacheSettings','CacheEnabled') != 'disabled')
{
    simpleForumCacheManager::getezcManager()->delete(null, array('type'=>'list_topic','id'=>$topic->attribute('node_id')),true);
}

if ($http->variable('ajax'))
{
        $Result = array();
        $Result['pagelayout'] = false;
        $Result['content']    = 0;
        return $Result;
}
else
{
    return $Module->redirectTo('/topic/list/'.$topic->attribute('node_id'));
}

?>
