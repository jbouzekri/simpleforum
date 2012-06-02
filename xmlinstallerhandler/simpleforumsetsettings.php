<?php

/**
 * File containing the simpleForumSetSettings class.
 *
 * @author jobou
 * @package simpleforum
 */

/*!
 @class simpleForumSetSettings simpleforumsetsettings.php
 @brief Used in ezxmlinstaller script Replace a string by another one in a file
*/

include_once('extension/ezxmlinstaller/classes/ezxmlinstallerhandler.php');

class simpleForumSetSettings extends eZXMLInstallerHandler
{

    /**
     * Constructor
     */
    function simpleForumSetSettings( )
    {
    }

    /**
     * Execute actions when ezxmlinstaller file has a SimpleForumSetSettings tag
     * 
     * @see eZXMLInstallerHandler::execute()
     * 
     * @param DOMElement $xml
     *   the SimpleForumSetSettings dom element
     */
    function execute( $xml )
    {
        $settingsFileList = $xml->getElementsByTagName( 'SettingsFile' );
        foreach ( $settingsFileList as $settingsFile )
        {
            $fileName = $settingsFile->getAttribute( 'name' );
            $location = $settingsFile->getAttribute( 'location' );
            $key      = $settingsFile->getAttribute( 'key' );
            $value    = $this->getReferenceID( $settingsFile->getAttribute( 'value' ) );

            $fileNamePath = $location . eZDir::separator( eZDir::SEPARATOR_LOCAL ) . $fileName;
            if (!is_writable($fileNamePath))
            {
                $this->writeMessage("The file $fileNamePath is not writable", 'error');
                continue;
            }
            
            $str = file_get_contents( $fileNamePath );
            $str = str_replace( $key, $value, $str);
            
            $fp=fopen( $fileNamePath, 'w' );
            fwrite( $fp, $str );
            fclose( $fp );
            
            $this->writeMessage("The file $fileNamePath has been updated");
        }
        eZCache::clearByID( array( 'ini', 'global_ini' ) );
    }

    /**
     * Define the xml tag name used by this handler
     * 
     * @static
     * 
     * @return array
     */
    static public function handlerInfo()
    {
        return array( 
            'XMLName' => 'SimpleForumSetSettings', 
            'Info' => 'manipulate settings files for simpleforum extension installation' 
        );
    }
}

