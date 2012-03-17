<?php

$Module = $Params['Module'];
$http   = eZHTTPTool::instance();

$newState = $Params['NewState'];
$topicID  = $Params['TopicID'];

$topic   = SimpleForumTopic::fetch($topicID);
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
if (!$topic->canRead())
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

if (in_array(strtoupper($newState), SimpleForumTopic::availableStates()))
{
    $topic->setAttribute('state', strtoupper($newState));
    $topic->store();
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
