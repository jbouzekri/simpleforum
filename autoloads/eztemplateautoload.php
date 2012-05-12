<?php

/**
 * @file
 * Define the new functions and operator provided by simpleforum extension
 * 
 * @author jobou
 * @package simpleforum
 */

// Init the function and operator array
$eZTemplateOperatorArray = array();
$eZTemplateFunctionArray[] = array( 'function' => 'simpleForumForwardInit',
                                    'function_names' => array( 
                                        'topic_view_gui',
                                        'response_view_gui'
                                    ) );

// Configure the functions
if ( !function_exists( 'simpleForumForwardInit' ) )
{
    function &simpleForumForwardInit()
    {
        $forward_rules = array(
            'topic_view_gui' => array( 'template_root' => 'topic/view',
                                       'input_name' => 'topic',
                                       'output_name' => 'topic',
                                       'namespace' => 'SimpleForumTopic',
                                       'attribute_access' => array( ),
                                       'use_views' => 'view' ),
            'response_view_gui' => array( 'template_root' => 'response/view',
                                          'input_name' => 'response',
                                          'output_name' => 'response',
                                          'namespace' => 'SimpleForumResponse',
                                          'attribute_access' => array( ),
                                          'use_views' => 'view' ) );

        $forwarder = new eZObjectForwarder( $forward_rules );
        return $forwarder;
    }
}

?>
