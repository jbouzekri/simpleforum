<?php

/**
 * @file
 * SimpleForumResponse object which represents a response to a forum topic
 *
 * @author jobou
 * @package simpleforum
 */
class SimpleForumResponse extends eZPersistentObject
{
    const SEARCH_TYPE = 'response';
    
    const STATUS_VALIDATED = 'VALIDATED';
    const STATUS_MODERATED = 'MODERATED';
    const STATUS_PUBLISHED = 'PUBLISHED';
     
    protected $topic = false;
    protected $user  = false;
     
     /**
     * Construct
     * use SimpleForumResponse::create
     * 
     * @param array $row
     */
    protected function __construct(  $row )
    {
        parent::eZPersistentObject( $row );
    }
 
    /**
     * Define the SimpleForumResponse attributes
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
                    'topic_id' => array(
                                       'name' => 'TopicID',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => true,
                                       'foreign_class' => 'simpleForumTopic',
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
                    'positive_vote' => array(
                                       'name' => 'PositiveVote',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => false ),
                    'total_vote' => array(
                                       'name' => 'TotalVote',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => false )
                  ),
                  'function_attributes' => array(
                      'topic'           => 'topic',
                      'user'            => 'responseUser',
                      'is_published'    => 'isPublished',
                      'is_validated'    => 'isValidated',
                      'is_moderated'    => 'isModerated',
                  	  'can_read'        => 'canRead',
                  	  'can_delete'      => 'canDelete',
                  	  'can_rate'        => 'canRate',
                  	  'can_reset_vote'  => 'canResetVote',
                  	  'rate'            => 'currentRate',
                      'language_code'   => 'languageCode',
                      'language_object' => 'languageObject',
                  ),
                  'keys' => array( 'id' ),
                  'increment_key' => 'id',
                  'class_name' => 'SimpleForumResponse',
                  'name' => 'simpleforum_response' );
        
        return $def;
    }
    
    /**
     * Return an array with the states availables for the SimpleForumResponse entity
     * 
     * @return array
     */
    public static function availableStates()
    {
        return array(
            self::STATUS_MODERATED,
            self::STATUS_PUBLISHED,
            self::STATUS_VALIDATED
        );
    }
    
    /**
     * Instanciate a SimpleForumResponse object
     * 
     * @param array $row
     * 
     * @return SimpleForumResponse
     */
    public static function create(array $row = array())
    {
        if (!isset($row['published'])) 
            $row['published'] = time();
        
        if (!isset($row['state']) 
            || !in_array($row['state'], self::availableStates())) 
            $row['state'] = self::STATUS_PUBLISHED;
        
        if (!isset($row['topic_id'])) 
        {
            $row['topic_id'] = 1;
        }
        
        if (!isset($row['user_id'])) 
        {
            $user = eZUser::currentUser();
            $row['user_id'] = $user->id();
        }
        
        $object = new self( $row );
        return $object;
    }
    
    /**
     * Fetch a list of SimpleForumResponse
     * 
     * @param array   $cond
     *   an array of condition to filter on SimpleForumResponse attributes
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
        if (!isset($cond['topic_id']))
        {
            $cond['topic_id'] = 1;
        }
        
        $list = eZPersistentObject::fetchObjectList( 
                self::definition(), null, $cond, $sortBy, $limit, $asObject 
        );
        return $list;
    }
    
    /**
     * Return an unique SimpleForumResponse
     * 
     * @param int     $id
     *   the id of the SimpleForumResponse to return
     * @param boolean $asObject
     *   Define if the SimpleForumResponse returns as an array or object
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
     * Count the number of SimpleForumResponse
     * 
     * @param array $filter
     *   filter the set of SimpleForumResponse to count
     * 
     * @return int
     */
    public static function fetchCount(array $filter = array())
    {
        return self::count( self::definition(), $filter );
    }
    
    /**
     * Return the topic of the response
     * 
     * @return SimpleForumTopic
     */
    public function topic()
    {
        if (!$this->topic)
        {
            $this->topic = SimpleForumTopic::fetch($this->attribute('topic_id'));
        }
        return $this->topic;
    }
    
    /**
     * Return the author of the response
     * 
     * @return eZUser
     */
    public function responseUser()
    {
        if (!$this->user)
        {
            $this->user = eZUser::fetch($this->attribute('user_id'));
        }
        
        return $this->user;
    }
    
    /**
     * Save in database the SimepleForumResponse
     * 
     * @see eZPersistentObject::store()
     */
    public function store( $fieldFilters = null )
    {
        $this->topic()->updateTopicModifiedDate();
        parent::store( $fieldFilters );
    }
    
    /**
     * Verify if the response is publised
     * 
     * @return boolean
     */
    public function isPublished()
    {
        return $this->attribute('state') == self::STATUS_PUBLISHED;
    }
    
    /**
     * Verify if the response is validated
     * 
     * @return boolean
     */
    public function isValidated()
    {
        return $this->attribute('state') == self::STATUS_VALIDATED;
    }
    
    /**
     * Verify if the response is moderated
     * 
     * @return boolean
     */
    public function isModerated()
    {
        return $this->attribute('state') == self::STATUS_MODERATED;
    }
    
    /**
     * Verify if the response is visible by classic user
     * 
     * @return boolean
     */
    public function isVisible()
    {
        return $this->attribute('state') != self::STATUS_MODERATED;
    }
    
    /**
     * Check if user can read the response
     * 
     * @return boolean
     */
    public function canRead()
    {
    	if ( !$this->topic()->canRead() || 
    		 (!self::showModeratedResponses() && $this->isModerated()) )
    	{
    		return false;
    	}
    	
    	return true;
    }
    
    /**
     * Check if user can delete the response
     *
     * @return boolean
     */
    public function canDelete()
    {
        if (!SimpleForumTools::checkAccess($this->topic()->forumNode(), 'response', 'remove'))
        {
        	return false;
        }
        
        return true;
    }
    
    /**
     * Verify if the configuration allow to show moderated items
     *
     * @return boolean
     */
    public static function showModeratedResponses()
    {
    	$ini = eZINI::instance('site.ini.append.php');
    	if ( !$ini->variable('SiteAccessSettings', 'ShowModeratedForumItems') )
    	{
    		return false;
    	}
    
    	return true;
    }
    
    /**
     * Check if user can rate the response
     *
     * @return boolean
     */
    public function canRate()
    {
    	if ($this->canRead() 
    		&& SimpleForumTools::checkAccess($this->topic()->forumNode(), 'response', 'rate'))
    	{
    		return true;
    	}
    	
    	return false;
    }
    
    /**
     * Add a positive vote to the reponse
     */
    public function addPositiveVote()
    {
    	$positiveVote = $this->attribute('positive_vote');
    	$totalVote    = $this->attribute('total_vote');
    	$positiveVote++;
    	$totalVote++;
    	$this->setAttribute( 'positive_vote', $positiveVote );
    	$this->setAttribute( 'total_vote', $totalVote );
        $this->store();
    }
    
    /**
     * Add a negative vote to the reponse
     */
    public function addNegativeVote()
    {
    	$totalVote = $this->attribute('total_vote');
    	$totalVote++;
    	$this->setAttribute( 'total_vote', $totalVote );
        $this->store();
    }
    
    /**
     * Check if the user can reset the vote count
     * 
     * @return boolean
     */
    public function canResetVote()
    {
    	if ($this->canRate() && SimpleForumTools::checkAccess($this->topic()->forumNode(), 'response', 'state'))
    	{
    		return true;
    	}
    	
    	return false;
    }
    
    /**
     * Reset the vote counter on the response
     */
    public function resetVote()
    {
    	$this->setAttribute( 'positive_vote', 0 );
    	$this->setAttribute( 'total_vote', 0 );
    	$this->store();
    }
    
    /**
     * Return the vote rate of the response
     * 
     * @return int
     */
    public function currentRate()
    {
    	if ($this->attribute('total_vote') > 0)
    	{
    		return ceil( ($this->attribute('positive_vote') / $this->attribute('total_vote')) * 100 );
    	}
    	
    	return -1;
    }
    
    /**
     * Transform the SimpleForumResponse in an array
     * Used in solr indexing task
     * 
     * @return array
     */
    public function toArray()
    {
        $array                  = array();
        $array['id']            = md5(self::SEARCH_TYPE.$this->attribute('id'));
        $array['entity_id']     = $this->attribute('id');
        $array['parent_id']     = $this->attribute('topic_id');
        $array['topic_id']      = $this->attribute('topic_id');
        $array['type']          = self::SEARCH_TYPE;
        $array['url']           = $this->topic()->urlAlias();
        $array['language_code'] = $this->topic()->languageCode();
        $array['content']       = $this->attribute('content');
        $array['published']     = $this->attribute('published');
        
        return $array;
    }
    
    /**
     * Return the SimpleForumResponseSearch entity associated with the response
     * 
     * @return simpleForumResponseSearch
     */
    public function getSearchObject()
    {
        $searchObject = new simpleForumResponseSearch();
        $searchObject->setState( $this->toArray() );
        return $searchObject;
    }
    
    /**
     * Get the language object of the response
     * 
     * @return eZContentLanguage
     */
    public function languageObject()
    {
        return $this->topic()->languageObject();
    }
    
    /**
     * Return the language code
     * 
     * @return string
     */
    function languageCode()
    {
        return $this->topic()->languageCode();
    }

}
?>