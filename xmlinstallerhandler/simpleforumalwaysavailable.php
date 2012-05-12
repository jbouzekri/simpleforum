<?php

/**
 * @file
 * Update an object to set the alwaysavailable parameter in ezxmlinstaller script
 *
 * @author jobou
 * @package simpleforum
 */

include_once('extension/ezxmlinstaller/classes/ezxmlinstallerhandler.php');

class simpleForumAlwaysAvailable extends eZXMLInstallerHandler
{

    function simpleForumAlwaysAvailable( )
    {
    }

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

    static public function handlerInfo()
    {
        return array( 'XMLName' => 'SimpleForumAlwaysAvailable', 'Info' => 'Set content translation always available' );
    }

}

