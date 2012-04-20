<?php

class simpleForumSolr implements ezpSearchEngine
{
    var $SolrINI;
    var $FindINI;
    var $SiteINI;
    
    function __construct()
    {
        $this->SolrINI = eZINI::instance( 'solr.ini' );
        $this->FindINI = eZINI::instance( 'ezfind.ini' );
        $this->SiteINI = eZINI::instance( 'site.ini' );
    }
    
	/**
	 * Whether a commit operation is required after adding/removing objects.
	 *
	 * @see commit()
	 * @return bool
	 */
	public function needCommit()
	{
		return true;
	}
	
	/**
	 * Whether calling removeObject() is required when updating an object.
	 *
	 * @see removeObject()
	 * @return bool
	 */
	public function needRemoveWithUpdate()
	{
		return false;
	}
	
	/**
	 * Adds object $contentObject to the search database.
	 *
	 * @param eZContentObject $contentObject Object to add to search engine
	 * @param bool $commit Whether to commit after adding the object
	 * @return bool True if the operation succeed.
	 */
	public function addObject( $contentObject, $commit = true )
	{
		return true;
	}
	
	/**
	 * Removes object $contentObject from the search database.
	 *
	 * @param eZContentObject $contentObject the content object to remove
	 * @param bool $commit Whether to commit after removing the object
	 * @return bool True if the operation succeed.
	 */
	public function removeObject( $contentObject, $commit = true )
	{
		return true;
	}
	
	/**
	 * Searches $searchText in the search database.
	 *
	 * @see supportedSearchTypes()
	 * @param string $searchText Search term
	 * @param array $params Search parameters
	 * @param array $searchTypes Search types
	 */
	public function search( $searchText, $params = array(), $searchTypes = array() )
	{
		return array();
	}
	
	/**
	 * Returns an array describing the supported search types by the search engine.
	 *
	 * @see search()
	 * @return array
	 */
	public function supportedSearchTypes()
	{
		return array();
	}
	
	/**
	 * Commit the changes made to the search engine.
	 *
	 * @see needCommit()
	 */
	public function commit()
	{
	    $this->Solr->commit();
	}
}