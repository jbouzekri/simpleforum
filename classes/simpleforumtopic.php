<?php
 
class SimpleForumTopic extends eZPersistentObject
{
     const STATUS_VALIDATED = 'VALIDATED';
     const STATUS_MODERATED = 'MODERATED';
     const STATUS_PUBLISHED = 'PUBLISHED';
     const STATUS_CLOSED    = 'CLOSED';
     
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
                  ),
                  'function_attributes' => array(
                      'forum_node' => 'forumNode',
                      'user'       => 'topicUser'
                  ),
                  'keys' => array( 'id' ),
                  'increment_key' => 'id',
                  'class_name' => 'SimpleForumTopic',
                  'name' => 'simpleforum_topic' );
        
        return $def;
    }
    
    public static function create(array $row = array())
    {
        if (!isset($row['published'])) 
            $row['published'] = time();
        
        if (!isset($row['state']) 
            || $row['state'] != self::STATUS_CLOSED
            || $row['state'] != self::STATUS_MODERATED
            || $row['state'] != self::STATUS_PUBLISHED
            || $row['state'] != self::STATUS_VALIDATED) 
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
    
    public static function fetchList(array $cond=array(), $limit = null, $sortBy = null, $asObject = false)
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
    
    public static function fetch($id, $asObject = false)
    {
        $cond = array( 'id' => $id );
        $topic = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $topic;
    }
    
    public function forumNode()
    {
        return eZContentObjectTreeNode::fetch($this->attribute('node_id'));
    }
    
    public function topicUser()
    {
        return eZUser::fetch($this->attribute('user_id'));
    }
}
?>