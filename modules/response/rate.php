<?php

/**
 * @file
 * response/rate view
 * Rate up/down a response.
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

$action      = $Params['Action'];
$responseID  = $Params['ResponseID'];

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

// Test if user can read topic page
if (!$response->canRate())
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

if ($action == 'up')
{
	$response->addPositiveVote();
}
elseif ($action == 'down')
{
	$response->addNegativeVote();
}
elseif ($action == 'reset' || $response->canResetVote())
{
	$response->resetVote();
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
