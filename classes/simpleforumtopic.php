<?php
 
class SimpleForumTopic extends eZPersistentObject
{
     const STATUS_VALIDATED = 'VALIDATED';
     const STATUS_MODERATED = 'MODERATED';
     const STATUS_PUBLISHED = 'PUBLISHED';
     const STATUS_CLOSED    = 'CLOSED';
     
     protected $forumNode = false;
     protected $user      = false;
     
     /**
     * Construct
     * use SimpleForumTopic::create
     * 
     * @param array $row
     */
    protected function __construct(  $row )
    {
        parent::eZPersistentObject( $row );
    }
 
    public static function definition()
    {
        static $def = array( 'fields' => array(
                    'id' => array(
                                       'name' => 'id',
                                       'datatype' => 'integer',
                                       'default' => '',
                                       'required' => true ),
                    'node_id' => array(
                                       'name' => 'NodeID',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => true,
                                       'foreign_class' => 'eZContentObjectTreeNode',
                                       'foreign_attribute' => 'id',
                                       'multiplicity' => '1..*'),
                    'user_id' => array(
                                       'name' => 'UserID',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => true,
                                       'foreign_class' => 'eZUser',
                                       'foreign_attribute' => 'contentobject_id',
                                       'multiplicity' => '1..*'),
                    'name' => array(
                                       'name' => 'Name',
                                       'datatype' => 'string',
                                       'default' => '',
                                       'required' => true ),
                    'content' => array(
                                       'name' => 'Content',
                                       'datatype' => 'text',
                                       'default' => '',
                                       'required' => true ),
                    'view_count' => array(
                                       'name' => 'ViewCount',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => false ),
                    'response_count' => array(
                                       'name' => 'ResponseCount',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => false ),
                    'state' => array(
                                       'name' => 'State',
                                       'datatype' => 'string',
                                       'default' => self::STATUS_PUBLISHED,
                                       'required' => true ),
                    'published' => array(
                                       'name' => 'Published',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => true ),
                     'modified' => array(
                                       'name' => 'Modified',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => true )
                  ),
                  'function_attributes' => array(
                      'forum_node'     => 'forumNode',
                      'user'           => 'topicUser',
                      'is_hidden'      => 'isHidden',
                      'is_published'   => 'isPublished',
                      'is_validated'   => 'isValidated',
                      'is_moderated'   => 'isModerated',
                      'is_closed'      => 'isClosed',
                      'is_visible'     => 'isVisible',
                  	  'can_read'       => 'canRead',
                  	  'can_delete'     => 'canDelete'
                  ),
                  'keys' => array( 'id' ),
                  'increment_key' => 'id',
                  'class_name' => 'SimpleForumTopic',
                  'name' => 'simpleforum_topic' );
        
        return $def;
    }
    
    public static function availableStates()
    {
        return array(
            self::STATUS_CLOSED,
            self::STATUS_MODERATED,
            self::STATUS_PUBLISHED,
            self::STATUS_VALIDATED
        );
    }
    
    public static function create(array $row = array())
    {
        if (!isset($row['published'])) 
            $row['published'] = time();
        
        if (!isset($row['modified'])) 
            $row['modified'] = time();
        
        if (!isset($row['state']) 
            || !in_array($row['state'], self::availableStates())) 
            $row['state'] = self::STATUS_PUBLISHED;
        
        if (!isset($row['node_id'])) 
        {
            $contentIni = eZINI::instance('content.ini.append.php');
            $row['node_id'] = $contentIni->variable('NodeSettings', 'ForumRootNode');
        }
        
        if (!isset($row['user_id'])) 
        {
            $user = eZUser::currentUser();
            $row['user_id'] = $user->id();
        }
        
        $object = new self( $row );
        return $object;
    }
    
    public static function fetchList(array $cond=array(), $limit = null, $sortBy = null, $asObject = true)
    {
        if (!isset($cond['node_id']))
        {
            $contentIni = eZINI::instance('content.ini.append.php');
            $cond['node_id'] = $contentIni->variable('NodeSettings', 'ForumRootNode');
        }
        
        $list = eZPersistentObject::fetchObjectList( 
                self::definition(), null, $cond, $sortBy, $limit, $asObject 
        );
        return $list;
    }
    
    public static function fetchCount(array $filter = array())
    {
        return self::count( self::definition(), $filter);
    }
    
    public static function removeByIds($ids)
    {
        $idList = array();
        if (is_array($ids))
        {
            $idList = array_merge($idList, $ids);
        }
        else
        {
            $idList[] = $ids;
        }

        $cond = array( 'id' => array( $idList ) );
        eZPersistentObject::removeObject( self::definition(), $cond );
    }
    
    public static function fetch($id, $asObject = true)
    {
        $cond = array( 'id' => $id );
        $topic = eZPersistentObject::fetchObject( self::definition(), null, $cond, $asObject );
        return $topic;
    }
    
    public function forumNode()
    {
        if (!$this->forumNode)
        {
            $this->forumNode = eZContentObjectTreeNode::fetch($this->attribute('node_id'));
        }
        return $this->forumNode;
    }
    
    public function topicUser()
    {
        if (!$this->user)
        {
            $this->user = eZUser::fetch($this->attribute('user_id'));
        }
        return $this->user;
    }
    
    public function incForumTopicCount()
    {
        $dataMap = $this->forumNode()->dataMap();
        $incTopic = (int) $dataMap['topic_count']->content();
        $incTopic++;
        $dataMap['topic_count']->fromString( $incTopic );
        $dataMap['topic_count']->store();
    }
    
    public function decForumTopicCount()
    {
        $dataMap = $this->forumNode()->dataMap();
        $decTopic = (int) $dataMap['topic_count']->content();
        $decTopic--;
        $dataMap['topic_count']->fromString( $decTopic );
        $dataMap['topic_count']->store();
    }
    
    public function incResponseCount()
    {
        $incResponse = (int) $this->attribute( 'response_count' );
        $incResponse++;
        $this->setAttribute( 'response_count', $incResponse );
        $this->store();
    }
    
    public function decResponseCount()
    {
        $decResponse = (int) $this->attribute( 'response_count' );
        $decResponse--;
        $this->setAttribute( 'response_count', $decResponse );
        $this->store();
    }
    
    public function incViewCount()
    {
        $incView = (int) $this->getAttribute( 'view_count' );
        $incView++;
        $this->setAttribute( 'view_count', $incView );
        $this->store();
    }
    
    public function updateTopicModifiedDate()
    {
        $this->setAttribute( 'modified', time() );
        $this->store();
    }
    
    public function fetchPath()
    {
        $path = array();
        foreach ($this->forumNode()->fetchPath() as $item)
        {
            $path[] = array(
                'url' => $item->urlAlias(),
                'text' => $item->Name
            );
        }
        
        $path[] = array(
            'url' => $this->forumNode()->urlAlias(),
            'text' => $this->forumNode()->Name
        );
        
        $path[] = array(
            'url' => '/topic/view/'.$this->attribute('id'),
            'text' => $this->attribute('name')
        );
        
        return $path;
    }
    
    public function isHidden()
    {
        $isHidden = false;
        if ($this->forumNode()->IsHidden)
        {
            $isHidden = true;
        }
        
        return $isHidden;
    }
    
    public function isPublished()
    {
        return $this->attribute('state') == self::STATUS_PUBLISHED;
    }
    
    public function isValidated()
    {
        return $this->attribute('state') == self::STATUS_VALIDATED;
    }
    
    public function isModerated()
    {
        return $this->attribute('state') == self::STATUS_MODERATED;
    }
    
    public function isClosed()
    {
        return $this->attribute('state') == self::STATUS_CLOSED;
    }
    
    public function isVisible()
    {
        return $this->attribute('state') != self::STATUS_MODERATED;
    }
    
    public function canRead()
    {
        // Test if forum Node is Hidden
        if ( !$this->forumNode()->canRead() || 
             ($this->forumNode()->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes()) ||
        	 !SimpleForumTools::checkAccess($this->forumNode()) )
        {
            return false;
        }
        
        // Test if topic is moderated
        if ( !self::showModeratedTopics() &&
        	 $this->isModerated() )
        {
        	return false;
        }
        
        return true;
    }
    
    public static function showModeratedTopics()
    {
    	$ini = eZINI::instance('site.ini.append.php');
    	if ( !$ini->variable('SiteAccessSettings', 'ShowModeratedForumItems') )
    	{
    		return false;
    	}
    	
    	return true;
    }
    
    public function canDelete()
    {
        if ( !SimpleForumTools::checkAccess($this->forumNode(), 'topic', 'remove') )
        {
        	return false;
        }
    	
        return true;
    }
}
