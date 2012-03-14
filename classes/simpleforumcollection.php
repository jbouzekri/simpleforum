<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of simpleforumtopiccollection
 *
 * @author jobou
 */
class SimpleForumCollection {
    
    function fetchTopic( $id )
    {
        $result = SimpleForumTopic::fetch($id);
        return array( 'result' => $result );
    }
    
    function fetchTopicList( $forumNodeId, $depth, $limit, $offset, $sortBy, $asObject, $attributeFilter )
    {
        $filter  = array();
        $filter['node_id'] = $this->getForumNodeIds($forumNodeId, $depth);
        
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
        
        $result = SimpleForumTopic::fetchList( $filter, $formatedLimit, $formatedSortBy, $asObject );
        
        return array( 'result' => $result );
    }
    
    public function fetchTopicCount( $forumNodeId, $depth, $attributeFilter )
    {
        $filter  = array();
        $filter['node_id'] = $this->getForumNodeIds($forumNodeId, $depth);
        
        $this->formatAttributeFilter($attributeFilter, $filter);
        
        $result = SimpleForumTopic::fetchCount( $filter );
        
        return array( 'result' => $result );
    }
    
    function fetchResponse( $id )
    {
        $result = SimpleForumResponse::fetch($id);
        return array( 'result' => $result );
    }
    
    function fetchResponseList($topicID, $limit, $offset, $sortBy, $asObject, $attributeFilter)
    {
        $filter  = array();
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
    
    public function fetchResponseCount( $topicID, $attributeFilter )
    {
        $filter  = array();
        $filter['topic_id'] = array(array($topicID));
        
        $this->formatAttributeFilter($attributeFilter, $filter);
        
        $result = SimpleResponseTopic::fetchCount( $filter );
        
        return array( 'result' => $result );
    }
    
    public function formatAttributeFilter($attributeFilter, &$filter)
    {
        if (is_array($attributeFilter) && isset($attributeFilter[0]))
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
    }
    
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
    
    public function getForumNodeIds($forumNodeId, $depth)
    {
        $nodeIDs = array($forumNodeId);
        if ($depth != 1)
        {
            $forums = eZContentObjectTreeNode::subTreeByNodeID(array('Depth'=>$depth), $forumNodeId);
            foreach ($forums as $forum)
            {
                $nodeIDs[] = $forum->attribute('node_id');
            }
        }
        return array($nodeIDs);
    }
}

?>
