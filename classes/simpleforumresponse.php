<?php
 
class SimpleForumResponse extends eZPersistentObject
{
     const STATUS_VALIDATED = 'VALIDATED';
     const STATUS_MODERATED = 'MODERATED';
     const STATUS_PUBLISHED = 'PUBLISHED';
     
     protected $topic = false;
     
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
                      'forum_node' => 'topic',
                      'user'       => 'responseUser'
                  ),
                  'keys' => array( 'id' ),
                  'increment_key' => 'id',
                  'class_name' => 'SimpleForumResponse',
                  'name' => 'simpleforum_response' );
        
        return $def;
    }
    
    public static function create(array $row = array())
    {
        if (!isset($row['published'])) 
            $row['published'] = time();
        
        if (!isset($row['state']) 
            || $row['state'] != self::STATUS_MODERATED
            || $row['state'] != self::STATUS_PUBLISHED
            || $row['state'] != self::STATUS_VALIDATED) 
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
    
    public static function fetchList(array $cond=array(), $limit = null, $sortBy = null, $asObject = false)
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
    
    public static function fetch($id, $asObject = false)
    {
        $cond = array( 'id' => $id );
        $topic = eZPersistentObject::fetchObject( self::definition(), null, $cond, $asObject );
        return $topic;
    }
    
    public function topic()
    {
        if (!$this->topic)
        {
            $this->topic = SimpleForumTopic::fetch($this->attribute('topic_id'));
        }
        return $this->topic;
    }
    
    public function responseUser()
    {
        return eZUser::fetch($this->attribute('user_id'));
    }
    
    public function store( $fieldFilters = null )
    {
        $this->topic()->updateTopicModifiedDate();
        parent::store( $fieldFilters );
    }
}
?>