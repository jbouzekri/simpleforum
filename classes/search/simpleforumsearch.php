<?php

/**
 * @file
 * Factory used to instanciate the forum search engine according
 * to the configuration
 *
 * @author jobou
 * @package simpleforum
 */
class simpleForumSearch
{
    /**
     * Configured search engine instance
     *
     * @var mixed $engine
     */
    protected $engine = false;
    
    /**
     * Singleton
     * 
     * @static
     * @var simpleForumSearch $instance
     */
    static protected $instance = false;
    
    /**
     * Constructor
     * Instanciate the search engine instance
     */
    protected function __construct()
    {
        $searchEngine = eZINI::instance()->variable('ForumSearchSettings', 'SearchEngine');
        $this->engine = new $searchEngine();
    } 
    
    /**
     * Return the singleton simpleForumSearch
     * 
     * @static
     * @return simpleForumSearch
     */
    public static function instance()
    {
        if ( !self::$instance )
        {
            // Could have trown strict error here but will cause issues if ini system has not been setup yet..
            self::$instance = new simpleForumSearch( );
        }
        
        return self::$instance;
    }
    
    /**
     * Get the configured search engine
     * 
     * @return mixed
     */
    public function getEngine()
    {
        return $this->engine;
    }
}