<?php

/**
 * File containing the simpleForumCacheManager class.
 *
 * @author jobou
 * @package simpleforum
 */

/*!
 @class simpleForumCacheManager simpleforumcachemanager.php
@brief Define a cache manager for simpleforum custom module/views 
*/

class simpleForumCacheManager
{
    /// The singleton
    public static $instance = false;
    
    /// The ezc cache storage file
    private $manager = false;
    
    /**
     * Constructor
     */
    protected function __construct()
    {
        $basePath = eZSys::rootDir().'/'.eZSys::cacheDirectory();
        if (!is_dir($basePath.'/simpleforum'))
        {
            mkdir($basePath.'/simpleforum', 0777, true);
        }
        
        $options = array(
            'ttl'   => 60*60*2
        );
        
        try
       {
           $this->manager = ezcCacheManager::getCache('simpleforum');
        }
        catch (ezcCacheInvalidIdException $e)
        {
            ezcCacheManager::createCache( 'simpleforum', $basePath.'/simpleforum', 'ezcCacheStoragePlain', $options );
            $this->manager = ezcCacheManager::getCache('simpleforum');
        }
    }
    
    /**
     * Instanciate a singleton of the simpleforum cache manager and return the ezc cache manager
     * 
     * @return ezcCacheStorageFile
     */
    public static function getezcManager()
    {
        if (!self::$instance)
        {
            self::$instance = new self();
        }
        
        return self::$instance->manager;
    }
    
    /**
     * Method call when purging cache in command line 
     */
    public static function purgeCache()
    {
        self::clearCache();
    }
    
    /**
     * Method call when clearing cache in command line
     */
    public static function clearCache()
    {
        $basePath = eZSys::rootDir().'/'.eZSys::cacheDirectory();
        if (is_dir($basePath.'/simpleforum'))
        {
            $caches = scandir($basePath.'/simpleforum');
            foreach ($caches as $file) {
                if ($file != "." && $file != "..") 
                {
                    if (filetype($basePath.'/simpleforum/'.$file) != "dir")
                    { 
                        unlink($basePath.'/simpleforum/'.$file);
                    }
                }
            }
        }
    }
}