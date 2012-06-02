<?php
/**
 * File containing the simpleForumResponseSearch class.
 * 
 * @author jobou
 * @package simpleforum
 */

/*!
 @class simpleForumResponseSearch simpleforumresponsesearch.php
 @brief Define the simpleForumResponseSearch class which is used by ezfind search engine
  for simpleForumResponse instance
*/

class simpleForumResponseSearch implements ezcBasePersistable, ezcSearchDefinitionProvider
{
    /// Define the type of entity in search index 
    const SEARCH_TYPE = 'response';
    
    /// Id of solr search index
    public $id;
    
    /// Id of the response
    public $entity_id;
    
    /// Id of the topic
    public $parent_id;
    
    /// type of the entity. In this class, its value is always "response"
    public $type;
    
    /// Url of the topic
    public $url;
    
    /// Language of the response
    public $language_code;
    
    /// Content of the response
    public $content;
    
    /// Publication/Creation date of the response. Timestamp.
    public $published;
    
    /// Last modification date of the response. Timestamp.
    public $modified;
    
    /// SimpleForumResponse object related to the solr response index
    public $ez_object = false;
    
    /**
     * Constructor
     */
    public function __construct()
    {     
    }
    
    /**
     * Return an array representing the object properties values
     * in order to index in solr
     * 
     * @see ezcBasePersistable::getState()
     * 
     * @return array
     */
    function getState()
    {
        $state = array(
            'id' => $this->id,
            'entity_id' => $this->entity_id,
            'parent_id' => $this->parent_id,
            'type' => $this->type,
            'url' => $this->url,
            'language_code' => $this->language_code,
            'content' => $this->content,
            'published' => $this->published,
            'modified' => $this->modified
        );
        
        return $state;
    }
    
    /**
     * Fill object attributes from an array
     * 
     * @see ezcBasePersistable::setState()
     * @see SimpleForumResponse::toArray()
     * 
     * @param array $state
     *   an associative array with data from a SimpleForumResponse entity
     */
    function setState( array $state )
    {
        if ( isset($state['id']) )
            $this->id = $state['id'];
        
        if ( isset($state['entity_id']) )
        {
            $this->entity_id = $state['entity_id'];
            $this->ez_object = SimpleForumResponse::fetch($state['entity_id']);
        }
        
        if ( isset($state['parent_id']) )
        {
            $this->parent_id = $state['parent_id'];
        }
        
        $this->type = isset($state['type']) ? $state['type'] : self::SEARCH_TYPE;
        
        if ( isset($state['url']) )
        {
            $this->url = $state['url'];
        }
        elseif ( isset($state['topic_id']) )
        {
            $this->url = '/topic/view/'.$state['topic_id'];
        }
        
        if ( isset($state['language_code']) )
            $this->language_code = $state['language_code'];
        
        if ( isset($state['content']) )
            $this->content = $state['content'];
        
        if ( isset($state['published']) )
        {
            if ( $state['published'] instanceof DateTime )
            {
                $this->published = $state['published']->format('U');
            }
            else
            {
                $this->published = date('c', $state['published']);
            }
        }
        
        if ( isset($state['modified']) )
        {
            if ( $state['modified'] instanceof DateTime )
            {
                $this->modified = $state['modified']->format('U');
            }
            else
            {
                $this->modified = date('c', $state['modified']);
            }
        }
    }
    
    /**
     * Configure the mapping between the entity and the solr schema
     */
    static public function getDefinition()
    {
        $n = new ezcSearchDocumentDefinition( __CLASS__ );
        
        $n->idProperty = 'id';
        
        $n->fields['id']             = new ezcSearchDefinitionDocumentField( 'id', ezcSearchDocumentDefinition::STRING );
        $n->fields['entity_id']      = new ezcSearchDefinitionDocumentField( 'entity_id', ezcSearchDocumentDefinition::INT );
        $n->fields['parent_id']      = new ezcSearchDefinitionDocumentField( 'parent_id', ezcSearchDocumentDefinition::INT );
        $n->fields['type']           = new ezcSearchDefinitionDocumentField( 'type', ezcSearchDocumentDefinition::STRING );
        $n->fields['url']            = new ezcSearchDefinitionDocumentField( 'url', ezcSearchDocumentDefinition::STRING );
        $n->fields['language_code']  = new ezcSearchDefinitionDocumentField( 'language_code', ezcSearchDocumentDefinition::STRING );
        $n->fields['content']        = new ezcSearchDefinitionDocumentField( 'content', ezcSearchDocumentDefinition::TEXT );
        $n->fields['published']      = new ezcSearchDefinitionDocumentField( 'published', ezcSearchDocumentDefinition::DATE );
        
        return $n;
    }
    
    /**
     * Return the SimpleForumResponse object associated to the current search item
     * 
     * @return SimpleForumResponse
     */
    public function getEzObject()
    {
        if ($this->ez_object)
        {
            return $this->ez_object;
        }
    
        if ($this->entity_id)
        {
            return SimpleForumResponse::fetch($this->entity_id);
        }
    
        return false;
    }
    
    /**
     * Return the value of a property. 
     * Used in template for attribute(show) operator
     * 
     * @param string $name
     *     The name of the property to return
     * 
     * @return string
     */
    public function attribute( $name )
    {
        if (property_exists($this, $name))
        {
            return $this->$name;
        }
    
        return '';
    }
    
    /**
     * Return the name of all properties. 
     * Used in template for attribute(show) operator
     *
     * @return array
     */
    public function attributes()
    {
        return array_keys(get_class_vars('simpleForumTopicSearch'));
    }
}
