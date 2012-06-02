<?php

/**
 * File containing the simpleForumAlwaysAvailable class.
 *
 * @author jobou
 * @package simpleforum
 */

/*!
 @class simpleForumAlwaysAvailable simpleforumalwaysavailable.php
 @brief Update an object to set the alwaysavailable parameter in ezxmlinstaller script
*/

include_once('extension/ezxmlinstaller/classes/ezxmlinstallerhandler.php');

class simpleForumAlwaysAvailable extends eZXMLInstallerHandler
{

    /**
     * Constructor
     */
    function simpleForumAlwaysAvailable( )
    {
    }

    /**
     * Execute actions when ezxmlinstaller file has a SimpleForumAlwaysAvailable tag
     *
     * @see eZXMLInstallerHandler::execute()
     *
     * @param DOMElement $xmlNode
     *   the SimpleForumAlwaysAvailable dom element
     */
    function execute( $xmlNode )
    {
        $objectID = $this->getReferenceID( $xmlNode->getAttribute( 'object' ) );
        $nodeID   = $this->getReferenceID( $xmlNode->getAttribute( 'node' ) );
        $newState = (bool) $xmlNode->getAttribute( 'newState' );
        
        if ( eZOperationHandler::operationIsAvailable( 'content_updatealwaysavailable' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content', 'updatealwaysavailable',
                                                            array( 
                                                                'object_id'            => $objectID,
                                                                'new_always_available' => $newState,
                                                                'node_id'              => $nodeID ) );
        }
        else
        {
            eZContentOperationCollection::updateAlwaysAvailable( $objectID, $newState );
        }
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
            'XMLName' => 'SimpleForumAlwaysAvailable', 
            'Info'    => 'Set content translation always available' 
        );
    }

}

