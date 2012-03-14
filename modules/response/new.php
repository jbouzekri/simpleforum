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

$name    = "";
$content = "";
$errors  = array();
if( $http->hasPostVariable('create') )
{
    $name    = $http->postVariable('name');
    if (!$name || $name == "")
    {
        $errors[] = ezpI18n::tr( 'simpleforum/response', 'Response name is required' );
    }
    elseif (strlen($name) > 200)
    {
        $errors[] = ezpI18n::tr( 'simpleforum/response', 'Response name is too long (200 characters max)' );
    }
    
    $content = $http->postVariable('content');
    if (!$content || $content == "")
    {
        $errors[] = ezpI18n::tr( 'simpleforum/response', 'Response content is required' );
    }
    elseif (strlen($content) < 200)
    {
        $errors[] = ezpI18n::tr( 'simpleforum/response', 'Response content must be at least 200 characters long.' );
    }
    
    $newResponse = SimpleForumResponse::create(array(
        'name' => $name,
        'content' => $content,
        'topic_id' => $topicID
    ));
    $newResponse->store();
    if ($newResponse->id)
    {
        return $Module->redirectTo('/topic/view/'.$topic->id);
    }
    else
    {
        $errors[] = ezpI18n::tr( 'simpleforum/response', 'An error occured when trying to create the new response' );
    }
}

$tpl->setVariable('topic_id', $topicID);
$tpl->setVariable('topic',    $topic);
$tpl->setVariable('name',     $name);
$tpl->setVariable('content',  $content);
$tpl->setVariable('errors',   $errors);

$tpl->setVariable('input_handler', new simpleForumXMLInput());

$Result = array();
$Result['content'] = $tpl->fetch( 'design:response/new.tpl' );
$Result['path'] = array( array( 'url' => 'response/new/'.$topicID,
                                'text' => 'Create New Topic' ) );

?>
