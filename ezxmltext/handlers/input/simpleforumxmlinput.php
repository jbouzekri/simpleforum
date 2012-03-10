<?php

class simpleForumXMLInput extends eZOEXMLInput
{
    function simpleForumXMLInput()
    {
        
    }
    
    /**
     * List of template callable attributes
     *
     * @return array
     */
    function attributes()
    {
        return array(
                'editor_layout_settings',
                'xml_tag_alias',
                'json_xml_tag_alias' );
    }

    /**
     * Function used by template system to call ezoe functions
     *
     * @param string $name
     * @return mixed
     */
    function attribute( $name )
    {
        if ( $name === 'editor_layout_settings' )
            $attr = self::getEditorGlobalLayoutSettings();
        else if ( $name === 'xml_tag_alias' )
            $attr =  self::getXmlTagAliasList();
        else if ( $name === 'json_xml_tag_alias' )
            $attr =  json_encode( self::getXmlTagAliasList() );
        else
            $attr = parent::attribute( $name );
        return $attr;
    }
    
     /**
     * getXmlTagAliasList
     * Get and chache XmlTagNameAlias from ezoe.ini
     *
     * @static
     * @return array
     */
    public static function getXmlTagAliasList()
    {
        if ( self::$xmlTagAliasList === null )
        {
            $ezoeIni = eZINI::instance( 'ezoe.ini' );
            self::$xmlTagAliasList = $ezoeIni->variable( 'SimpleForumEditorSettings', 'XmlTagNameAlias' );
        }
        return self::$xmlTagAliasList;
    }

     /**
     * isValid
     * Called by handler loading code to see if this is a valid handler.
     *
     * @return bool
     */
    function isValid()
    {
        if ( !$this->currentUserHasAccess() )
        {
            eZDebug::writeNotice('Current user does not have access to ezoe, falling back to normal xml editor!', __METHOD__ );
            return false;
        }

        if ( !self::browserSupportsDHTMLType() )
        {
            eZDebug::writeWarning('Current browser is not supported by ezoe', __METHOD__ );
            return false;
        }

        return true;
    }

     /**
     * currentUserHasAccess
     *
     * @param string $view name of ezoe view to check for access on
     * @return bool
     */
    function currentUserHasAccess( $view = 'editor' )
    {
        if ( !isset( self::$userAccessHash[ $view ] ) )
        {
            self::$userAccessHash[ $view ] = false;
            $user = eZUser::currentUser();
            if ( $user instanceOf eZUser )
            {
                $result = $user->hasAccessTo( 'ezoe', $view );
                if ( $result['accessWord'] === 'yes'  )
                {
                    self::$userAccessHash[ $view ] = true;
                }
                else if ( $result['accessWord'] === 'limited' )
                {
                     foreach ( $result['policies'] as $pkey => $limitationArray )
                     {
                        foreach ( $limitationArray as $key => $valueList  )
                        {
                            switch( $key )
                            {
                                case 'User_Section':
                                {
                                    if ( in_array( $this->ContentObjectAttribute->attribute('object')->attribute( 'section_id' ), $valueList ) )
                                    {
                                        self::$userAccessHash[ $view ] = true;
                                        break 3;
                                    }
                                } break;
                                case 'User_Subtree':
                                {
                                    $node = $this->ContentObjectAttribute->attribute('object')->attribute('main_node');
                                    if ( !$node instanceof eZContentObjectTreeNode )
                                    {
                                        // get temp parent node if object don't have node assignmet yet
                                        $tempParentNodeId = $this->ContentObjectAttribute->attribute('object_version')->attribute('main_parent_node_id');
                                        $node = eZContentObjectTreeNode::fetch( $tempParentNodeId );
                                    }
                                    $path = $node->attribute( 'path_string' );
                                    foreach ( $valueList as $subtreeString )
                                    {
                                        if ( strstr( $path, $subtreeString ) )
                                        {
                                            self::$userAccessHash[ $view ] = true;
                                            break 4;
                                        }
                                    }
                                } break;
                            }
                        }
                     }
                }
            }
        }
        return self::$userAccessHash[ $view ];
    }

     /**
     * getEditorGlobalLayoutSettings
     * used by {@link eZOEXMLInput::getEditorLayoutSettings()}
     *
     * @static
     * @return array hash with global layout settings for the editor
     */
    public static function getEditorGlobalLayoutSettings()
    {
        if ( self::$editorGlobalLayoutSettings === null )
        {
            $oeini = eZINI::instance( 'ezoe.ini' );
            self::$editorGlobalLayoutSettings = array(
                'buttons' => $oeini->variable('SimpleForumEditorLayout', 'Buttons' ),
                'toolbar_location' => $oeini->variable('SimpleForumEditorLayout', 'ToolbarLocation' ),
                'path_location' => $oeini->variable('SimpleForumEditorLayout', 'PathLocation' ),
            );
        }
        return self::$editorGlobalLayoutSettings;
    }
}

?>
