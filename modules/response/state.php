<?php

eZDebug::updateSettings(array(
		"debug-enabled" => false,
		"debug-by-ip" => false
));

$Module = $Params['Module'];
$http   = eZHTTPTool::instance();

$newState   = $Params['NewState'];
$responseID = $Params['ResponseID'];

$response = SimpleForumResponse::fetch($responseID);
if (!$responseID || !$response)
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

// Test if user can read topic page and can change state
if (!$response->topic()->canRead() || !SimpleForumTools::checkAccess($response->topic()->forumNode(), 'response', 'state'))
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

if (in_array(strtoupper($newState), SimpleForumResponse::availableStates()))
{
    $response->setAttribute('state', strtoupper($newState));
    $response->store();
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
    return $Module->redirectTo('/topic/view/'.$response->attribute('topic_id'));
}

?>
