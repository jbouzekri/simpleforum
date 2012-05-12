<?php

/**
 * @file
 * topic/inc_view_count view
 * Increment the counter of topic view number.
 *
 * @author jobou
 * @package simpleforum
 */

eZDebug::updateSettings(array(
		"debug-enabled" => false,
		"debug-by-ip" => false
));

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$topicID = $Params['TopicID'];
$topic   = SimpleForumTopic::fetch($topicID);
if (!$topicID || !$topic)
{
    $Result = array();
	$Result['content'] = 0;
	$Result['pagelayout'] = false;
	return $Result;
}

// Test if user can read topic page
if (!$topic->canRead())
{
    $Result = array();
	$Result['content'] = 0;
	$Result['pagelayout'] = false;
	return $Result;
}

if (isset($_COOKIE["topic_view"]))
{
	$tmpArray = explode('|', $_COOKIE["topic_view"]);
	if (!in_array($topicID, $tmpArray))
	{
		$tmpArray[] = $topicID;
		$topic->incViewCount();
		setcookie("topic_view", implode('|', $tmpArray), time()+24*3600, '/');
	}
}
else
{
	$topic->incViewCount();
	setcookie("topic_view", $topicID, time()+24*3600, '/');
}

$Result = array();
$Result['content'] = 1;
$Result['pagelayout'] = false;
