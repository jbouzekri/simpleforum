<?php

class simpleForumSearch
{
    protected $engine = false;
    static protected $instance = false;
    
    protected function __construct()
    {
        $searchEngine = eZINI::instance()->variable('ForumSearchSettings', 'SearchEngine');
        $this->engine = new $searchEngine();
    } 
    
    public static function instance()
    {
        if ( !self::$instance )
        {
            // Could have trown strict error here but will cause issues if ini system has not been setup yet..
            self::$instance = new simpleForumSearch( );
        }
        
        return self::$instance;
    }
    
    public function getEngine()
    {
        return $this->engine;
    }
}