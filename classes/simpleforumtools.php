<?php

/**
 * Description of simpleforumtopiccollection
 *
 * @author jobou
 */
class SimpleForumTools {
	
	public static function checkAccess(eZContentObjectTreeNode $forumNode, $moduleName = 'topic', $functionName = 'read')
	{
		if (!$forumNode)
		{
			return false;
		}
	
		$user = eZUser::currentUser();
		$accessResult = $user->hasAccessTo( $moduleName , $functionName );
		if ($accessResult['accessWord'] == 'yes')
		{
			return true;
		}
		elseif ($accessResult['accessWord'] == 'limited')
		{
			$policies = $accessResult['policies'];
			$access = 'denied';
	
			foreach ( $policies as $pkey => $limitationArray )
			{
				if ( $access == 'allowed' )
				{
					break;
				}
	
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
}