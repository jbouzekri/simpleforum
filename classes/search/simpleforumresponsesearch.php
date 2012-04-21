<?php

class simpleForumResponseSearch implements ezcBasePersistable, ezcSearchDefinitionProvider
{
    const SEARCH_TYPE = 'response';
    
    public $id;
    public $entity_id;
    public $type;
    public $url;
    public $language_code;
    public $text;
    public $published;
    public $modified;
    
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
            'text' => $this->text,
            'published' => $this->published,
            'modified' => $this->modified
        );
        
        return $state;
    }
    
    function setState( array $state )
    {
        $this->id            = $state['id'];
        $this->entity_id     = $state['entity_id'];
        $this->type          = self::SEARCH_TYPE;
        $this->url           = '/topic/view/'.$state['topic_id'];
        $this->language_code = 'fre-FR';
        $this->text          = $state['content'];
        $this->published     = $state['published'];
    }
    
    static public function getDefinition()
    {
        $n = new ezcSearchDocumentDefinition( __CLASS__ );
        
        $n->idProperty = 'id';
        
        $n->fields['entity_id']     = new ezcSearchDefinitionDocumentField( 'entity_id', ezcSearchDocumentDefinition::INT );
        $n->fields['type']          = new ezcSearchDefinitionDocumentField( 'type', ezcSearchDocumentDefinition::STRING );
        $n->fields['url']           = new ezcSearchDefinitionDocumentField( 'url', ezcSearchDocumentDefinition::STRING );
        $n->fields['language_code'] = new ezcSearchDefinitionDocumentField( 'language_code', ezcSearchDocumentDefinition::STRING );
        $n->fields['text']          = new ezcSearchDefinitionDocumentField( 'text', ezcSearchDocumentDefinition::TEXT );
        $n->fields['published']     = new ezcSearchDefinitionDocumentField( 'published', ezcSearchDocumentDefinition::DATE );
        
        return $n;
    }
}
?>
