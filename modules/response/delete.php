<?php

$Module = $Params['Module'];
$http   = eZHTTPTool::instance();

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
if (!$response->canDelete())
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

$response->remove();

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
