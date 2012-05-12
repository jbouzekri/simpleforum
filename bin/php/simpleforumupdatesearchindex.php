#!/usr/bin/env php
<?php

/**
 * @file
 * Update the search engine index with forum data
 * 
 * @author jobou
 * @package simpleforum
 */

require 'autoload.php';

set_time_limit( 0 );

// Init CLI and script instance
$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' =>
            "simpleForum search index updater.\n\n" .
            "Goes trough all forum topics and response and reindexes the data to the search engine" .
            "\n" .
            "simpleforumupdatesearchindex.php",
        'use-session' => true,
        'use-modules' => true,
        'use-extensions' => true
    )
);

$options = $script->getOptions(
		"[db-host:][db-user:][db-password:][db-database:][db-type:|db-driver:][clean]",
		"",
		array(
				'db-host'     => "Database host",
				'db-user'     => "Database user",
				'db-password' => "Database password",
				'db-database' => "Database name",
				'db-driver'   => "Database driver",
				'db-type'     => "Database driver, alias for --db-driver",
				'clean'       => "Remove all search data"
		)
);
$script->initialize();

global $isQuiet;

// Fix siteaccess
$siteaccess = $options['siteaccess'] ? $options['siteaccess'] : false;
if ( $siteaccess )
{
    if ( !in_array( $siteaccess, eZINI::instance()->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' ) ) )
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $siteaccess does not exist, using default siteaccess" );
    }
}

// Get configured search engine
$searchEngine = eZINI::instance()->variable('ForumSearchSettings', 'SearchEngine');
if (!$searchEngine)
{
    $cli->error( "No defined search engine" );
    $script->shutdown( 1 );
}

// If solr, check if solr server is running
if ($searchEngine == 'simpleForumSolr')
{
    $solrINI = eZINI::instance( 'solr.ini' );
    
    $host = $solrINI->variable('ForumSolrBase', 'SearchServerHost');
    $port = $solrINI->variable('ForumSolrBase', 'SearchServerPort');
    $path = $solrINI->variable('ForumSolrBase', 'SearchServerPath');
    
    $url = 'http://'.$host.':'.$port.$path;
    
    $solrBase = new eZSolrBase( $url );
    $pingResult = $solrBase->ping();
    if ( !isset( $pingResult['status'] ) || $pingResult['status'] !== 'OK' )
    {
        $cli->error( "Cannot ping solr server" );
        $script->shutdown( 1 );
    }
}

// Instantiate search engine
$simpleForumSearch = new $searchEngine();

if ( $options['clean'] )
{
    $cli->output( "Cleaning up all forum search data" );
    $simpleForumSearch->cleanUp();
}

// Fetch topic to index
$topics = SimpleForumTopic::fetchList();
foreach ($topics as $topic)
{
    // Index topic
    $simpleForumSearch->addObject( $topic );
    
    // Index responses
    foreach ($topic->getAllResponses() as $response)
    {
        $simpleForumSearch->addObject( $response );
    }
}

// Check if search engine need commit
if ($simpleForumSearch->needCommit())
{
    $simpleForumSearch->commit();
}

$script->shutdown( 0 );

?>
