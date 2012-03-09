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
    
    function fetchTopicList( $forumNodeId, $depth, $limit, $offset, $sortBy )
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
        
        $formatedLimit = null;
        if ($limit)
        {
            $formatedLimit = array(
                'offset' => $offset,
                'length' => $limit
            );
        }
        
        $result = SimpleForumTopic::fetchList(array(
            'node_id' => array($nodeIDs)
        ), $formatedLimit, $formatedSortBy);
        
        return array( 'result' => $result );
    }
}

?>
