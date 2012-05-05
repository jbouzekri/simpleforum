<?php

class simpleForumTopicSearch implements ezcBasePersistable, ezcSearchDefinitionProvider
{
    const SEARCH_TYPE = 'topic';
    
    public $id;
    public $entity_id;
    public $type;
    public $url;
    public $language_code;
    public $content;
    public $published;
    public $modified;
    
    public $ez_object = false;
    
    public function __construct()
    {     
    }
    
    function getState()
    {
        $state = array(
            'id' => $this->id,
            'entity_id' => $this->entity_id,
            'type' => $this->type,
            'url' => $this->url,
            'language_code' => $this->language_code,
            'content' => $this->content,
            'published' => $this->published,
            'modified' => $this->modified
        );
        
        return $state;
    }
    
    function setState( array $state )
    {
        if ( isset($state['id']) )
            $this->id = $state['id'];
        
        if ( isset($state['entity_id']) )
        {
            $this->entity_id = $state['entity_id'];
            $this->ez_object = SimpleForumTopic::fetch($state['entity_id']);
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
    
    static public function getDefinition()
    {
        $n = new ezcSearchDocumentDefinition( __CLASS__ );
        
        $n->idProperty = 'id';
        
        $n->fields['id']             = new ezcSearchDefinitionDocumentField( 'id', ezcSearchDocumentDefinition::STRING );
        $n->fields['ezcsearch_type'] = new ezcSearchDefinitionDocumentField( 'ezcsearch_type', ezcSearchDocumentDefinition::STRING );
        $n->fields['entity_id']      = new ezcSearchDefinitionDocumentField( 'entity_id', ezcSearchDocumentDefinition::INT );
        $n->fields['type']           = new ezcSearchDefinitionDocumentField( 'type', ezcSearchDocumentDefinition::STRING );
        $n->fields['url']            = new ezcSearchDefinitionDocumentField( 'url', ezcSearchDocumentDefinition::STRING );
        $n->fields['language_code']  = new ezcSearchDefinitionDocumentField( 'language_code', ezcSearchDocumentDefinition::STRING );
        $n->fields['content']        = new ezcSearchDefinitionDocumentField( 'content', ezcSearchDocumentDefinition::TEXT );
        $n->fields['published']      = new ezcSearchDefinitionDocumentField( 'published', ezcSearchDocumentDefinition::DATE );
        $n->fields['modified']       = new ezcSearchDefinitionDocumentField( 'modified', ezcSearchDocumentDefinition::DATE, 0 );
        
        return $n;
    }
    
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
}
?>
