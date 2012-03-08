<?php
 
class SimpleForumTopic extends eZPersistentObject
{
     const STATUS_VALIDATED = 'VALIDATED';
     const STATUS_MODERATED = 'MODERATED';
     const STATUS_PUBLISHED = 'PUBLISHED';
     const STATUS_CLOSED    = 'CLOSED';
     
     /**
     * Construct
     * 
     * @param array $row
     */
    public function __construct(  $row )
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
                                       'name' => 'node_id',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => true,
                                       'foreign_class' => 'eZContentObjectTreeNode',
                                       'foreign_attribute' => 'id',
                                       'multiplicity' => '1..*'),
                    'user_id' => array(
                                       'name' => 'user_id',
                                       'datatype' => 'integer',
                                       'default' => 0,
                                       'required' => true,
                                       'foreign_class' => 'eZUser',
                                       'foreign_attribute' => 'contentobject_id',
                                       'multiplicity' => '1..*'),
                    'name' => array(
                                       'name' => 'name',
                                       'datatype' => 'string',
                                       'default' => '',
                                       'required' => true ),
                    'content' => array(
                                       'name' => 'content',
                                       'datatype' => 'text',
                                       'default' => '',
                                       'required' => true ),
                    'state' => array(
                                       'name' => 'state',
                                       'datatype' => 'string',
                                       'default' => self::STATUS_PUBLISHED,
                                       'required' => true ),
                    'created_at' => array(
                                       'name' => 'created_at',
                                       'datatype' => 'integer',
                                       'default' => '',
                                       'required' => true ),
                  ),
                  'keys' => array( 'id' ),
                  'increment_key' => 'id',
                  'class_name' => 'SimpleForumTopic',
                  'name' => 'simpleforum_topic' );
        
        return $def;
    }
}
?>