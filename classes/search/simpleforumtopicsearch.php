<?php

/**
 * File containing the simpleForumTopicSearch class.
 *
 * @author jobou
 * @package simpleforum
 */

/*!
 @class simpleForumTopicSearch simpleforumtopicsearch.php
 @brief Define the simpleForumTopicSearch class which is used by ezfind search engine 
for simpleForumTopic instance
*/

class simpleForumTopicSearch implements ezcBasePersistable, ezcSearchDefinitionProvider
{
    /**
     * Define the type of entity in search index
     */
    const SEARCH_TYPE = 'topic';
    
    /**
     * Id of solr search index
     *
     * @var int $id
     */
    public $id;
    
    /**
     * Id of the topic
     *
     * @var int $entity_id
     */
    public $entity_id;
    
    /**
     * forum node id parent of the topic
     *
     * @var int $parent_id
     */
    public $parent_id;
    
    /**
     * type of the entity
     * In this class, its value is always "topic"
     *
     * @var string $type
     */
    public $type;
    
    /**
     * Url of the topic
     *
     * @var string $url
     */
    public $url;
    
    /**
     * Language of the topic
     *
     * @var string $language_code
     */
    public $language_code;
    
    /**
     * Content of the topic
     *
     * @var string $content
     */
    public $content;
    
    /**
     * Publication/Creation date of the topic
     *
     * @var int $published
     */
    public $published;
    
    /**
     * Last modification date of the topic
     *
     * @var int $modified
     */
    public $modified;
    
    /**
     * SimpleForumTopic object related to the solr response index
     *
     * @var SimpleForumTopic $ez_object
     */
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
     * @see SimpleForumTopic::toArray()
     *
     * @param array $state
     *   an associative array with data from a SimpleForumTopic entity
     */
    function setState( array $state )
    {
        if ( isset($state['id']) )
            $this->id = $state['id'];
        
        if ( isset($state['entity_id']) )
        {
            $this->entity_id = $state['entity_id'];
            $this->ez_object = SimpleForumTopic::fetch($state['entity_id']);
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
        elseif ( isset($state['entity_id']) )
        {
            $this->url = '/topic/view/'.$state['entity_id'];
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
        $n->fields['modified']       = new ezcSearchDefinitionDocumentField( 'modified', ezcSearchDocumentDefinition::DATE );
        
        return $n;
    }
    
    /**
     * Return the SimpleForumTopic object associated to the current search item
     *
     * @return SimpleForumTopic
     */
    public function getEzObject()
    {
        if ($this->ez_object)
        {
            return $this->ez_object;
        }
        
        if ($this->entity_id)
        {
            return SimpleForumTopic::fetch($this->entity_id);
        }
        
        return false;
    }
    
    /**
     * Return the value of a property
     * used in template for attribute(show) operator
     *
     * @param string $name
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
     * Return the name of all properties
     * used in template for attribute(show) operator
     *
     * @return array
     */
    public function attributes()
    {
        return array_keys(get_class_vars('simpleForumTopicSearch'));
    }
}
