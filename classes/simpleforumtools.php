<?php

/**
 * File containing the SimpleForumTools class.
 *
 * @author jobou
 * @package simpleforum
 */

/*!
 @class SimpleForumTools simpleforumtools.php
 @brief Some functions used in simpleforum code
*/

class SimpleForumTools {
	
    /**
     * Verify if the current user have a permission on topics
     * 
     * @see eZContentObjectTreeNode::checkAccess
     * 
     * @param eZContentObjectTreeNode $forumNode
     *   the top forum node parent of the topic
     * @param string                  $moduleName
     *   the module name. Default is topic.
     * @param string                  $functionName
     *   the function name. Default is read.
     * 
     * @return boolean
     */
	public static function checkAccess(eZContentObjectTreeNode $forumNode, $moduleName = 'topic', $functionName = 'read')
	{
		if (!$forumNode)
		{
			return false;
		}
	
		$user = eZUser::currentUser();
		
		// Check if user has unlimited access to module/function
		$accessResult = $user->hasAccessTo( $moduleName , $functionName );
		if ($accessResult['accessWord'] == 'yes')
		{
			return true;
		}
		elseif ($accessResult['accessWord'] == 'limited')
		{
		    // User has limited access to module/function
			$policies = $accessResult['policies'];
			$access = 'denied';
	
			foreach ( $policies as $pkey => $limitationArray )
			{
				if ( $access == 'allowed' )
				{
					break;
				}
	
				// List limitation condition
				$limitationList = array();
				if ( isset( $limitationArray['Subtree' ] ) )
				{
					$checkedSubtree = false;
				}
				else
				{
					$checkedSubtree = true;
					$accessSubtree = false;
				}
				if ( isset( $limitationArray['Node'] ) )
				{
					$checkedNode = false;
				}
				else
				{
					$checkedNode = true;
					$accessNode = false;
				}
	
				// For each limitation, check if the user has the module/function
				foreach ( $limitationArray as $key => $valueList  )
				{
					$access = 'denied';
					switch( $key )
					{
						case 'Node':
							{
								$accessNode = false;
								foreach ( $valueList as $nodeID )
								{
									$node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
									$limitationNodeID = $node['main_node_id'];
									if ( $forumNode->attribute('node_id') == $limitationNodeID )
									{
										$access = 'allowed';
										$accessNode = true;
										break;
									}
								}
								if ( $access != 'allowed' && $checkedSubtree && !$accessSubtree )
								{
									$access = 'denied';
								}
								else
								{
									$access = 'allowed';
								}
								$checkedNode = true;
							} break;
	
						case 'Subtree':
							{
								$accessSubtree = false;
								$path = $forumNode->attribute( 'path_string' );
								$subtreeArray = $valueList;
								foreach ( $subtreeArray as $subtreeString )
								{
									if ( strstr( $path, $subtreeString ) )
									{
										$access = 'allowed';
										$accessSubtree = true;
										break;
									}
								}
								if ( $access != 'allowed' && $checkedNode && !$accessNode )
								{
									$access = 'denied';
								}
								else
								{
									$access = 'allowed';
								}
								$checkedSubtree = true;
							} break;
					}
	
					if ( $access == 'denied' )
					{
						break;
					}
				}
			}
	
			if ( $access == 'denied' )
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	
	/**
	 * Return a real eZContentLanguage id
	 * 
	 * @param int   $forumNodeId
	 *   the top forum node to get the current language if $language is unknown
	 * @param mixed $language
	 *   a code or id of eZContentLanguage to check
	 * 
	 * @return mixed
	 */
	public static function getLanguageId($forumNodeId, $language = false)
	{
	    if (in_array($language, eZContentLanguage::fetchLocaleList()))
	    {
	        $languageObject = eZContentLanguage::fetchByLocale($language);
	        return $languageObject->attribute('id');
	    }
	    
	    if (is_int($language))
	    {
	        $languageObject = eZContentLanguage::fetch($language);
	        if ($languageObject)
	        {
	            return $language;
	        }
	    }
	    
	    $forum = eZContentObjectTreeNode::fetch($forumNodeId);
	    $languageObject = $forum->object()->currentLanguageObject();
	    if ($languageObject)
	    {
	        return $languageObject->attribute('id');
	    }
	    
	    eZDebug::writeError('Unable to find a language to instance topic');
	    return false;
	}
}