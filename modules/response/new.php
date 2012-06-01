<?php

/**
 * @file
 * response/new view
 * Create a new response.
 *
 * @author jobou
 * @package simpleforum
 */

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$topicID = $Params['TopicID'];
$topic   = SimpleForumTopic::fetch($topicID);
if (!$topicID || !$topic)
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

// Test if user can read topic page and create a response
if (!$topic->canRead() || !SimpleForumTools::checkAccess($topic->forumNode(), 'response', 'create'))
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

$tpl = eZTemplate::factory();

$name    = "";
$content = "";
$errors  = array();
if( $http->hasPostVariable('NewButton') || $Module->isCurrentAction('New') )
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
    
    if (!count($errors))
    {
        $newResponse = SimpleForumResponse::create(array(
            'name' => htmlspecialchars($name),
            'content' => htmlspecialchars($content),
            'topic_id' => $topicID
        ));
        $newResponse->store();
        if ($newResponse->id)
        {
            $topic->incResponseCount();
            $topic->updateTopicModifiedDate();
            eZContentCacheManager::clearContentCacheIfNeeded( $newResponse->topic()->forumNode()->object()->ID );
            return $Module->redirectTo('/topic/view/'.$topic->id);
        }
        else
        {
            $errors[] = ezpI18n::tr( 'simpleforum/response', 'An error occured when trying to create the new response' );
        }
    }
}
elseif ( $http->hasPostVariable('CancelButton') || $Module->isCurrentAction('Cancel') )
{
    return $Module->redirectTo('/topic/view/'.$topicID);
}

$tpl->setVariable('topic_id', $topicID);
$tpl->setVariable('topic',    $topic);
$tpl->setVariable('name',     $name);
$tpl->setVariable('content',  $content);
$tpl->setVariable('errors',   $errors);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:response/new.tpl' );
$Result['path'] = array( array( 'url' => 'response/new/'.$topicID,
                                'text' => 'Create New Topic' ) );

?>
