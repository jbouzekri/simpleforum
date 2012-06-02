<?php

/**
 * File containing the SimpleForumCollection class.
 *
 * @author jobou
 * @package simpleforum
 */

/*!
 @class SimpleForumCollection simpleforumcollection.php
 @brief Define all custom fetch function used by this extension
*/

class SimpleForumCollection {
    
    /**
     * Fetch a unique topic
     * fetch('topic','object') function
     * 
     * @param int $id
     * 
     * @return array
     */
    function fetchTopic( $id )
    {
        $result = SimpleForumTopic::fetch($id);
        return array( 'result' => $result );
    }
    
    /**
     * Fetch a list of topic
     * fetch('topic','list') function
     * 
     * @param int     $forumNodeId
     *   The node Id of the parent forum node
     * @param int     $depth
     *   The maximum depth to fetch topic
     * @param int     $limit
     *   The number maximum of topic to return
     * @param int     $offset
     *   Define an offset to return a topic set 
     * @param array   $sortBy
     *   Sort configuration for returned topic set
     * @param boolean $asObject
     *   Define if the returned topic set contains array or object
     * @param array   $attributeFilter
     *   Filter the returned topic set on a SimpleForumTopic attribute
     * @param array   $limitation
     *   Bypass the read permission
     * @param string  $language
     *   language of the topics
     *   
     * @return array
     */
    function fetchTopicList( $forumNodeId, $depth, $limit, $offset, $sortBy, $asObject, $attributeFilter, $limitation, $language )
    {
        $filter  = array();
        $forumNode = eZContentObjectTreeNode::fetch($forumNodeId);
    	if (!SimpleForumTools::checkAccess($forumNode) && !is_array($limitation))
    	{
        	return array( 'result' => array() );
    	}
    	
        $filter['node_id'] = $this->getForumNodeIds($forumNodeId, $depth, $limitation);
        
        $this->formatAttributeFilter($attributeFilter, $filter);
        
        if (!is_array($language))
        {
            $filter['language_id'] = SimpleForumTools::getLanguageId($forumNodeId, $language);
        }
        
        $formatedSortBy = $this->formatSortBy($sortBy);
        
        $formatedLimit = null;
        if ($limit)
        {
            $formatedLimit = array(
                'offset' => $offset,
                'length' => $limit
            );
        }
        
        $result = SimpleForumTopic::fetchList( $filter, $formatedLimit, $formatedSortBy, $asObject );
        
        return array( 'result' => $result );
    }
    
    /**
     * Count the number of topic
     * fetch('topic','list_count') function
     * 
     * @param int    $forumNodeId
     *   The node Id of the parent forum node
     * @param int    $depth
     *   The maximum depth to fetch topic
     * @param array  $attributeFilter
     *   Filter the returned topic set on a SimpleForumTopic attribute
     * @param array  $limitation
     *   Bypass the read permission
     * @param string $language
     *   language of the topics
     * 
     * @return array
     */
    public function fetchTopicCount( $forumNodeId, $depth, $attributeFilter, $limitation, $language )
    {
        $filter  = array();
        
        $forumNode = eZContentObjectTreeNode::fetch($forumNodeId);
        if (!SimpleForumTools::checkAccess($forumNode) && !is_array($limitation))
        {
        	return array( 'result' => 0 );
        }
        
        $filter['node_id'] = $this->getForumNodeIds($forumNodeId, $depth, $limitation);
        
        $this->formatAttributeFilter($attributeFilter, $filter);
        
        if (!is_array($language))
        {
            $filter['language_id'] = SimpleForumTools::getLanguageId($forumNodeId, $language);
        }
        
        $result = SimpleForumTopic::fetchCount( $filter );
        
        return array( 'result' => $result );
    }
    
    /**
     * Fetch a unique response
     * fetch('response','object') function
     *
     * @param int $id
     *
     * @return array
     */
    function fetchResponse( $id )
    {
        $result = SimpleForumResponse::fetch($id);
        return array( 'result' => $result );
    }
    
    /**
     * Fetch a list of responses
     * fetch('response','list') function
     * 
     * @param int     $topicID
     *   The Id of the parent topic
     * @param int     $limit
     *   The number maximum of response to return
     * @param int     $offset
     *   Define an offset to return a response set 
     * @param array   $sortBy
     *   Sort configuration for returned response set
     * @param boolean $asObject
     *   Define if the returned response set contains array or object
     * @param array   $attributeFilter
     *   Filter the returned response set on a SimpleForumResponse attribute
     * @param array   $limitation
     *   Bypass the read permission
     * 
     * @return array
     */
    function fetchResponseList($topicID, $limit, $offset, $sortBy, $asObject, $attributeFilter, $limitation)
    {
        $filter  = array();
        
        $topic = SimpleForumTopic::fetch($topicID);
        if ( !$topic || 
             (!$topic->canRead() && !is_array($limitation)) )
        {
        	return array( 'result' => array() );
        }
        
        $filter['topic_id'] = array(array($topicID));
        
        $this->formatAttributeFilter($attributeFilter, $filter);
        
        $formatedSortBy = $this->formatSortBy($sortBy);
        
        $formatedLimit = null;
        if ($limit)
        {
            $formatedLimit = array(
                'offset' => $offset,
                'length' => $limit
            );
        }
        
        $result = SimpleForumResponse::fetchList( $filter, $formatedLimit, $formatedSortBy, $asObject );
        
        return array( 'result' => $result );
    }
    
    /**
     * Count the number of response
     * fetch('response','list_count') function
     * 
     * @param int   $topicID
     *   The Id of the parent topic
     * @param array $attributeFilter
     *   Filter the returned response set on a SimpleForumResponse attribute
     * @param array $limitation
     *   Bypass the read permission
     * 
     * @return array
     */
    public function fetchResponseCount( $topicID, $attributeFilter, $limitation )
    {
        $filter  = array();
        
        $topic = SimpleForumTopic::fetch($topicID);
        if ( !$topic ||
             (!$topic->canRead() && !is_array($limitation)) )
        {
        	return array( 'result' => 0 );
        }
        
        $filter['topic_id'] = array(array($topicID));
        
        $this->formatAttributeFilter($attributeFilter, $filter);
        
        $result = SimpleForumResponse::fetchCount( $filter );
        
        return array( 'result' => $result );
    }
    
    /**
     * Format the attribute_filter array passed by template in fetch functions
     * 
     * @param array $attributeFilter
     *   the attribute_filter array passed by fetch functions
     * @param array $filter
     *   the filter array used by eZPersistenObject::fetchObjectList function
     *   
     * @return array
     */
    public function formatAttributeFilter($attributeFilter, &$filter)
    {
        if (is_array($attributeFilter) && isset($attributeFilter[0]) && is_array($attributeFilter[0]))
        {
            foreach ($attributeFilter as $filterItem)
            {
                if (is_array($filterItem) && isset($filterItem[0]) && count($filterItem) == 3)
                {
                    if ($filterItem[1] == '=')
                    {
                        $filter[$filterItem[0]] = $filterItem[2];
                    }
                    else
                    {
                        $filter[$filterItem[0]] = array($filterItem[1], $filterItem[2]);
                    }
                }
            }
        }
        elseif (is_array($attributeFilter) && isset($attributeFilter[0]) && count($attributeFilter) == 3)
        {
            if ($attributeFilter[1] == '=')
            {
                $filter[$attributeFilter[0]] = $attributeFilter[2];
            }
            else
            {
                $filter[$attributeFilter[0]] = array($attributeFilter[1], $attributeFilter[2]);
            }
        }
    }
    
    /**
     * Format the sort_by array passed by template in fetch functions
     * 
     * @param array $sortBy
     *   the sort_by array passed by fetch functions
     * 
     * @return array
     */
    public function formatSortBy($sortBy)
    {
        $formatedSortBy = null;
        if (is_array($sortBy))
        {
            if (is_array($sortBy[0]))
            {
                foreach ($sortBy as $sortItem)
                {
                    $formatedSortBy[$sortItem[0]] = $sortItem[1];
                }
            }
            else
            {
                $formatedSortBy[$sortBy[0]] = $sortBy[1];
            }
        }
        
        return $formatedSortBy;
    }
    
    /**
     * Return an array of forum node ids
     * 
     * @param int   $forumNodeId
     *   the id of the top forum node
     * @param int   $depth
     *   the maximum depth to fetch
     * @param array $limitation
     *   Bypass the read permission
     * 
     * @return array
     */
    public function getForumNodeIds($forumNodeId, $depth, $limitation = false)
    {
        $nodeIDs = array($forumNodeId);
        if ($depth != 1)
        {
            $forums = eZContentObjectTreeNode::subTreeByNodeID(array('Depth'=>$depth), $forumNodeId);
            foreach ($forums as $forum)
            {
            	if (SimpleForumTools::checkAccess($forum) || is_array($limitation))
            	{
                	$nodeIDs[] = $forum->attribute('node_id');
            	}
            }
        }
        return array($nodeIDs);
    }
    
    /**
     * Search and fetch a list of topic
     * fetch('topic','search') function
     * 
     * @param string $query
     *   the query text
     * @param int    $forumNodeId
     *   the top forum node in which to start searching
     * @param int    $limit
     *   Number maximum of topic to return
     * @param int    $offset
     *   Set an offset to return a topic set
     * @param array  $sortBy
     *   Order the returned topic set
     * @param array  $attributeFilter
     *   Filter the returned topic set on a SimpleForumTopic attribute or an attribute of the index
     * @param string $language
     *   Filter the returned topic set by language
     * 
     * @return array
     */
    public function searchTopic( $query, $forumNodeId, $limit, $offset, $sortBy, $attributeFilter, $language )
    {
        $parameters = array();
        if ( $limit !== false)
            $parameters['limit'] = $limit;
        
        if ( $offset !== false)
            $parameters['offset'] = $offset;
        
        if ( $forumNodeId !== false)
            $parameters['parent_node_id'] = $forumNodeId;
        
        if ( is_array($sortBy) && !empty($sortBy) )
            $parameters['sort_by'] = $sortBy;
        
        if (is_array($attributeFilter) && !empty($attributeFilter))
        {
            if (!is_array($attributeFilter[0]))
            {
                $parameters['attribute_filter'] = array($attributeFilter);
            }
            else
           {
               $parameters['attribute_filter'] = $attributeFilter;
            }
        }
        
        $searchResult = array();
        if ($engine = simpleForumSearch::instance()->getEngine())
        {
            $searchResult = $engine->search( $query,
                    $parameters,
                    'simpleForumTopicSearch' );
        }
        
        return array( 'result' => $searchResult );
    }
    
    /**
     * Search and fetch a list of response
     * fetch('response','search') function
     * 
     * @param string $query
     *   the query text
     * @param int $topicId
     *   the topic id to define the topic in which to start searching
     * @param int $limit
     *   Number maximum of response to return
     * @param int $offset
     *   Set an offset to return a response set
     * @param array $sortBy
     *   Order the returned response set
     * @param array $attributeFilter
     *   Filter the returned response set on a SimpleForumResponse attribute or an attribute of the index
     * 
     * @return array
     */
    public function searchResponse( $query, $topicId, $limit, $offset, $sortBy, $attributeFilter )
    {
        $parameters = array();
        if ( $limit !== false)
            $parameters['limit'] = $limit;
    
        if ( $offset !== false)
            $parameters['offset'] = $offset;
    
        if ( $topicId !== false)
            $parameters['parent_node_id'] = $topicId;
    
        if ( is_array($sortBy) && !empty($sortBy) )
            $parameters['sort_by'] = $sortBy;
    
        if (is_array($attributeFilter) && !empty($attributeFilter))
        {
            if (!is_array($attributeFilter[0]))
            {
                $parameters['attribute_filter'] = array($attributeFilter);
            }
            else
            {
                $parameters['attribute_filter'] = $attributeFilter;
            }
        }
    
        $searchResult = array();
        if ($engine = simpleForumSearch::instance()->getEngine())
        {
            $searchResult = $engine->search( $query,
                    $parameters,
                    'simpleForumResponseSearch' );
        }
    
        return array( 'result' => $searchResult );
    }
}
