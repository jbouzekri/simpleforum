<?php

/**
 * @file
 * SimpleForumTopic object which represents a forum topic
 *
 * @author jobou
 * @package simpleforum
 */ 
class SimpleForumTopic extends eZPersistentObject
{
     const STATUS_VALIDATED = 'VALIDATED';
     const STATUS_MODERATED = 'MODERATED';
     const STATUS_PUBLISHED = 'PUBLISHED';
     const STATUS_CLOSED    = 'CLOSED';
     
     const SEARCH_TYPE = 'topic';
     
     protected $forumNode = false;
     protected $user      = false;
     protected $language  = false;
     
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
 
    /**
     * Define the SimpleForumTopic attributes
     *
     * @return array
     */
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
                                       'required' => true ),
                     'language_id' => array(
                                       'name' => 'LanguageID',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => true,
                                       'foreign_class' => 'eZContentLanguage',
                                       'foreign_attribute' => 'id',
                                       'multiplicity' => '1..*' )
                  ),
                  'function_attributes' => array(
                      'forum_node'      => 'forumNode',
                      'user'            => 'topicUser',
                      'is_hidden'       => 'isHidden',
                      'is_published'    => 'isPublished',
                      'is_validated'    => 'isValidated',
                      'is_moderated'    => 'isModerated',
                      'is_closed'       => 'isClosed',
                      'is_visible'      => 'isVisible',
                  	  'can_read'        => 'canRead',
                  	  'can_delete'      => 'canDelete',
                      'language_code'   => 'languageCode',
                      'language_object' => 'languageObject',
                      'url_alias'       => 'urlAlias'
                  ),
                  'keys' => array( 'id' ),
                  'increment_key' => 'id',
                  'class_name' => 'SimpleForumTopic',
                  'name' => 'simpleforum_topic' );
        
        return $def;
    }
    
    /**
     * Return an array with the states availables for the SimpleForumTopic entity
     *
     * @return array
     */
    public static function availableStates()
    {
        return array(
            self::STATUS_CLOSED,
            self::STATUS_MODERATED,
            self::STATUS_PUBLISHED,
            self::STATUS_VALIDATED
        );
    }
    
    /**
     * Instanciate a SimpleForumTopic object
     *
     * @param array $row
     *
     * @return SimpleForumTopic
     */
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
        
        if (!isset($row['language_id']))
        {
            $row['language_id'] = false;
        }
        $row['language_id'] = SimpleForumTools::getLanguageId($row['node_id'], $row['language_id']);
        
        $object = new self( $row );
        return $object;
    }
    
    /**
     * Store a SimpleForumTopic object in database
     * Generate an url_alias
     * 
     * @see eZPersistentObject::store()
     * 
     * @param mixed $fieldFilters
     *    If specified only certain fields will be stored.
     */
    public function store($fieldFilters = null)
    {
        parent::store($fieldFilters);
        
        $parentAlias = eZURLAliasML::fetchByAction( 
                "eznode", 
                $this->attribute('node_id') 
        );
        if (count($parentAlias))
        {
            $text = eZURLAliasML::findUniqueText(
                $parentAlias[0]->attribute('id'),
                eZURLAliasML::convertToAlias($this->attribute('name'))
            );
            $urlAliasMl = eZURLAliasML::create(
                    $text,
                    'module:topic/view/'.$this->attribute('id'), 
                    $parentAlias[0]->attribute('id'), 
                    $this->languageObject()->attribute('id')
            );
            $urlAliasMl->store();
        }
    }
    
    /**
     * Fetch a list of SimpleForumTopic
     *
     * @param array   $cond
     *   an array of condition to filter on SimpleForumTopic attributes
     * @param int     $limit
     *   the maximum number of object to return
     * @param array   $sortBy
     *   the sorting order configuration
     * @param boolean $asObject
     *   define if the method return an array of object or array
     *
     * @return array
     */
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
    
    /**
     * Count the number of SimpleForumTopic
     * 
     * @param array $filter
     *   filter the set of SimpleForumTopic to count
     * 
     * @return int
     */
    public static function fetchCount(array $filter = array())
    {
        return self::count( self::definition(), $filter);
    }
    
    /**
     * Remove a list of topic by ids
     * 
     * @param mixed $ids
     *   the id of SimpleForumTopic to remove
     */
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
    
    /**
     * Return an unique SimpleForumTopic
     *
     * @param int     $id
     *   the id of the SimpleForumTopic to return
     * @param boolean $asObject
     *   Define if the SimpleForumTopic returns as an array or object
     *
     * @return mixed
     */
    public static function fetch($id, $asObject = true)
    {
        $cond = array( 'id' => $id );
        $topic = eZPersistentObject::fetchObject( self::definition(), null, $cond, $asObject );
        return $topic;
    }
    
    /**
     * Return the forum node parent of the topic
     *
     * @return eZContentObjectTreeNode
     */
    public function forumNode()
    {
        if (!$this->forumNode)
        {
            $this->forumNode = eZContentObjectTreeNode::fetch(
                $this->attribute('node_id'),
                $this->languageCode()
            );
        }
        return $this->forumNode;
    }
    
    /**
     * Get the author of the topic
     * 
     * @return eZUser
     */
    public function topicUser()
    {
        if (!$this->user)
        {
            $this->user = eZUser::fetch($this->attribute('user_id'));
        }
        return $this->user;
    }
    
    /**
     * Increment the counter of topics in forum node
     */
    public function incForumTopicCount()
    {
        $dataMap = $this->forumNode()->dataMap();
        $incTopic = (int) $dataMap['topic_count']->content();
        $incTopic++;
        $dataMap['topic_count']->fromString( $incTopic );
        $dataMap['topic_count']->store();
    }
    
    /**
     * Decrement the counter of topics in forum node
     */
    public function decForumTopicCount()
    {
        $dataMap = $this->forumNode()->dataMap();
        $decTopic = (int) $dataMap['topic_count']->content();
        $decTopic--;
        $dataMap['topic_count']->fromString( $decTopic );
        $dataMap['topic_count']->store();
    }
    
    /**
     * Increment the response count in the topic
     */
    public function incResponseCount()
    {
        $incResponse = (int) $this->attribute( 'response_count' );
        $incResponse++;
        $this->setAttribute( 'response_count', $incResponse );
        $this->store();
    }
    
    /**
     * Decrement the response count in the topic
     */
    public function decResponseCount()
    {
        $decResponse = (int) $this->attribute( 'response_count' );
        $decResponse--;
        $this->setAttribute( 'response_count', $decResponse );
        $this->store();
    }
    
    /**
     * Increment the view counter in the topic
     */
    public function incViewCount()
    {
        $incView = (int) $this->attribute( 'view_count' );
        $incView++;
        $this->setAttribute( 'view_count', $incView );
        $this->store();
    }
    
    /**
     * Update the last modified date of the topic
     */
    public function updateTopicModifiedDate()
    {
        $this->setAttribute( 'modified', time() );
        $this->store();
    }
    
    /**
     * Return the path of the topic
     * 
     * @return array
     */
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
            'url' => $this->urlAlias(),
            'text' => $this->attribute('name')
        );
        
        return $path;
    }
    
    /**
     * Check if the topic is hidden by the forum node
     * 
     * @return boolean
     */
    public function isHidden()
    {
        $isHidden = false;
        if ($this->forumNode()->IsHidden)
        {
            $isHidden = true;
        }
        
        return $isHidden;
    }
    
    /**
     * Check if the topic has been published
     * 
     * @return boolean
     */
    public function isPublished()
    {
        return $this->attribute('state') == self::STATUS_PUBLISHED;
    }
    
    /**
     * Check if the topic has been validated
     *
     * @return boolean
     */
    public function isValidated()
    {
        return $this->attribute('state') == self::STATUS_VALIDATED;
    }
    
    /**
     * Check if the topic has been moderated
     *
     * @return boolean
     */
    public function isModerated()
    {
        return $this->attribute('state') == self::STATUS_MODERATED;
    }
    
    /**
     * Check if the topic has been closed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->attribute('state') == self::STATUS_CLOSED;
    }
    
    /**
     * Check if the topic is visible.
     * For the moment, it means that it has not been moderated
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->attribute('state') != self::STATUS_MODERATED;
    }
    
    /**
     * Check if the current user can read the topic
     *
     * @return boolean
     */
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
    
    /**
     * Check if the settings show moderated forum is enabled
     * 
     * @return boolean
     */
    public static function showModeratedTopics()
    {
    	$ini = eZINI::instance('site.ini.append.php');
    	if ( !$ini->variable('SiteAccessSettings', 'ShowModeratedForumItems') )
    	{
    		return false;
    	}
    	
    	return true;
    }
    
    /**
     * Check if the current user can delete a topic
     * 
     * @return boolean
     */
    public function canDelete()
    {
        if ( !SimpleForumTools::checkAccess($this->forumNode(), 'topic', 'remove') )
        {
        	return false;
        }
    	
        return true;
    }
    
    /**
     * Return the current topic object in array
     * Used to index in solr
     * 
     * @return array
     */
    public function toArray()
    {
        $array                  = array();
        $array['id']            = md5(self::SEARCH_TYPE.$this->attribute('id'));
        $array['entity_id']     = $this->attribute('id');
        $array['parent_id']     = $this->attribute('node_id');
        $array['type']          = self::SEARCH_TYPE;
        $array['url']           = $this->urlAlias();
        $array['language_code'] = $this->languageCode();
        $array['content']       = $this->attribute('content');
        $array['published']     = $this->attribute('published');
        $array['modified']      = $this->attribute('modified');
        return $array;
    }
    
    /**
     * Return the solr search object associated with the topic
     * 
     * @return simpleForumTopicSearch
     */
    public function getSearchObject()
    {
        $searchObject = new simpleForumTopicSearch();
        $searchObject->setState( $this->toArray() );
        return $searchObject;
    }
    
    /**
     * Return all the responses of the topic
     * 
     * @return array
     */
    public function getAllResponses()
    {
        return SimpleForumResponse::fetchList(array('topic_id'=>$this->attribute('id')));
    }
    
    /**
     * Return the language object of the topic
     * 
     * @return eZContentLanguage
     */
    public function languageObject()
    {
        if (!$this->language)
        {
            $this->language = $this->attribute('language_id') ? eZContentLanguage::fetch( $this->attribute('language_id') ) : false;
        }
        
        return $this->language;
    }
    
    /**
     * Return the locale of the topic
     * 
     * @return string
     */
    public function languageCode()
    {
        $languageObject = $this->languageObject();
        return ( $languageObject !== false ) ?  $languageObject->attribute( 'locale' ) : false;
    }
    
    /**
     * Get the full url alias of the topic
     * 
     * @return string
     */
    public function urlAlias()
    {
        $useURLAlias =& $GLOBALS['eZContentObjectTreeNodeUseURLAlias'];
        $ini         = eZINI::instance();
        $cleanURL    = '';
        
        if ( !isset( $useURLAlias ) )
        {
            $useURLAlias = $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled';
        }
        
        if ( $useURLAlias )
        {
            $aliases = eZURLAliasML::fetchByAction('module', 'topic/view/'.$this->attribute('id'));
            if (count($aliases))
            {
                $cleanURL = $this->forumNode()->urlAlias().'/'.$aliases[0]->attribute('text');
            }
            else
           {
               $cleanURL = 'topic/view/'.$this->attribute('id');
            }   
        }
        else
       {
           $cleanURL = 'topic/view/'.$this->attribute('id');
        }
        
        return $cleanURL;
    }
}
