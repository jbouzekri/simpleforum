<?php
 
class SimpleForumImage extends eZPersistentObject
{
     /**
     * Construct
     * use SimpleForumTopic::create
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
                    'path' => array(
                                       'name' => 'Path',
                                       'datatype' => 'string',
                                       'default' => '',
                                       'required' => true),
                    'mime' => array(
                                       'name' => 'Mime',
                                       'datatype' => 'string',
                                       'default' => '',
                                       'required' => true)
                  ),
                  'keys' => array( 'id' ),
                  'increment_key' => 'id',
                  'class_name' => 'SimpleForumImage',
                  'name' => 'simpleforum_image' );
        
        return $def;
    }
    
    public static function fetch($id, $asObject = false)
    {
        $cond = array( 'id' => $id );
        $image = eZPersistentObject::fetchObject( self::definition(), null, $cond, $asObject );
        return $image;
    }
    
    public static function fetchByPath($path, $asObject = false)
    {
        $cond = array( 'path' => $path );
        $image = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $image;
    }
}
?>