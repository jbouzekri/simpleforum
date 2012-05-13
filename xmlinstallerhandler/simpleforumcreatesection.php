<?php

/**
 * @file
 * Create a new section in ezxmlinstaller script
 * Copied from ezxmlinstaller handler to fix some bugs
 *
 * @author jobou
 * @package simpleforum
 */

include_once('extension/ezxmlinstaller/classes/ezxmlinstallerhandler.php');

class simpleForumCreateSection extends eZXMLInstallerHandler
{
    /**
     * Constructor
     */
    function simpleForumCreateSection( )
    {
    }

    /**
     * Execute actions when ezxmlinstaller file has a SimpleForumCreateSection tag
     *
     * @see eZXMLInstallerHandler::execute()
     *
     * @param DOMElement $xml
     *   the SimpleForumCreateSection dom element
     */
    function execute( $xmlNode )
    {
        // ezcontentnavigationpart
        $sectionName    = $xmlNode->getAttribute( 'sectionName' );
        $sectionIdentifier    = $xmlNode->getAttribute( 'sectionIdentifier' );
        $navigationPart = $xmlNode->getAttribute( 'navigationPart' );
        $referenceID    = $xmlNode->getAttribute( 'referenceID' );

        $sectionID = false;
        if( $sectionIdentifier )
        {
            $section = eZSection::fetchByIdentifier( $sectionIdentifier );
            if ($section)
            {
                $sectionID = $section->attribute( 'id' );
            }
        }

        if( !$sectionID )
        {
            $sectionID = $this->sectionIDbyName( $sectionName );
        }

        if( $sectionID )
        {
            $this->writeMessage( "\tSection '$sectionName' already exists." , 'notice' );
        }
        else
        {
            $section = new eZSection( array() );
            $section->setAttribute( 'name', $sectionName );
            $section->setAttribute( 'identifier', $sectionIdentifier );
            $section->setAttribute( 'navigation_part_identifier', $navigationPart );
            $section->store();
            $sectionID = $section->attribute( 'id' );
        }
        
        $refArray = array();
        if ( $referenceID )
        {
            $refArray[$referenceID] = $sectionID;
        }
        $this->addReference( $refArray );
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
            'XMLName' => 'SimpleForumCreateSection',
            'Info'    => 'create new section' 
        );
    }

    /**
     * Return the id of a section when knowing its name
     * 
     * @param string $name
     *   the name of the section
     *   
     * @return int|boolean
     */
    private function sectionIDbyName( $name )
    {
        $sectionID = false;
        $sectionList = eZSection::fetchFilteredList( array( 'name' => $name ), false, false, true );
        if( is_array( $sectionList ) && count( $sectionList ) > 0 )
        {
            $section = $sectionList[0];
            if( is_object( $section ) )
            {
                $sectionID = $section->attribute( 'id' );
            }
        }
        return $sectionID;
    }

}
