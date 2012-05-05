<?php

class simpleForumSolr implements ezpSearchEngine
{
    var $SolrINI;
    
    var $handler;
    var $manager;
    var $session;
    
    function __construct()
    {
        $this->SolrINI = eZINI::instance( 'solr.ini' );

        $host = $this->SolrINI->variable('ForumSolrBase', 'SearchServerHost');
        $port = $this->SolrINI->variable('ForumSolrBase', 'SearchServerPort');
        $path = $this->SolrINI->variable('ForumSolrBase', 'SearchServerPath');
        
        $this->handler = new ezcSearchSolrHandler( $host, $port, $path );
        $this->manager = new ezcSearchEmbeddedManager;
        $this->session = new ezcSearchSession( $this->handler, $this->manager );
        
        $this->handler->beginTransaction();
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
	public function addObject( $object, $commit = true )
	{
        $doc = $object->getSearchObject();        
        $this->session->index( $doc );
        
        // Reconnect at each update because of a reset connection problem
        $this->handler->reConnect();
        
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
	 * 
	 * @param string $searchText  Search term
	 * @param array  $params      Search parameters
	 * @param array  $searchTypes Search types
	 */
	public function search( $searchText, $params = array(), $searchTypes = array() )
	{
	    // initialize a pre-configured query
	    $q = $this->session->createFindQuery( 'simpleForumTopicSearch' );
	    
	    // limit the query and order
	    $q->limit( 10 );
	    
	    // run the query and show titles for found documents
	    $r = $this->session->find( $q );
	    
	    $result = array();
	    foreach( $r->documents as $res )
	    {
	        $result[] = $res->document;
	    }
	    
		return $result;
	}
	
	/**
	 * Returns an array describing the supported search types by the search engine.
	 *
	 * @see search()
	 * @return array
	 */
	public function supportedSearchTypes()
	{
		return array(
		    simpleForumResponseSearch::SEARCH_TYPE,
		    simpleForumTopicSearch::SEARCH_TYPE        
		);
	}
	
	/**
	 * Commit the changes made to the search engine.
	 *
	 * @see needCommit()
	 */
	public function commit()
	{
	    // Reconnect at each update because of a reset connection problem
	    $this->handler->reConnect();
	    
	    $this->handler->commit();
	}
	
	/**
	 * Clean entire index
	 *
	 * @see needCommit()
	 */
	public function cleanUp()
	{    
        $deleteResponse = $this->session->createDeleteQuery('simpleForumResponseSearch');
        $this->handler->delete( $deleteResponse );
        
        $deleteTopic = $this->session->createDeleteQuery('simpleForumTopicSearch');
        $this->handler->delete( $deleteTopic );
	}
}