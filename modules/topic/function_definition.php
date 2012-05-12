<?php

/**
 * @file
 * Define all fetch topics functions
 *
 * @author jobou
 * @package simpleforum
 */

$FunctionList = array();
$FunctionList['object'] = array( 'name' => 'object',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 
                                   'class' => 'SimpleForumCollection',
                                   'method' => 'fetchTopic' 
                               ),
                               'parameter_type' => 'standard',
                               'parameters' => array(
                                   array( 'name'     => 'id',
                                          'type'     => 'integer',
                                          'required' => true,
                                          'default'  => 1
                                        )
                                )
);
$FunctionList['list'] = array( 'name' => 'list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 
                                   'class' => 'SimpleForumCollection',
                                   'method' => 'fetchTopicList' 
                               ),
                               'parameter_type' => 'standard',
                               'parameters' => array(
                                   array( 'name'     => 'forum_node_id',
                                          'type'     => 'integer',
                                          'required' => true,
                                          'default'  => 1
                                        ),
                                   array( 'name'     => 'depth',
                                          'type'     => 'integer',
                                          'required' => false,
                                          'default'  => 1
                                   ),
                                   array( 'name'     => 'limit',
                                          'type'     => 'integer',
                                          'required' => false,
                                          'default'  => NULL
                                   ),
                                   array( 'name'     => 'offset',
                                          'type'     => 'integer',
                                          'required' => false,
                                          'default'  => 0
                                   ),
                                   array( 'name'     => 'sort_by',
                                          'type'     => 'array',
                                          'required' => false,
                                          'default'  => array('published', 'asc')
                                   ),
                                   array( 'name'     => 'as_object',
                                          'type'     => 'boolean',
                                          'required' => false,
                                          'default'  => true
                                   ),
                                   array( 'name'     => 'attribute_filter',
                                          'type'     => 'array',
                                          'required' => false,
                                          'default'  => array()
                                   ),
                               	   array( 'name'     => 'limitation',
                               		      'type'     => 'array',
                               			  'required' => false,
                               			  'default'  => false
                               	),
                               	   array( 'name'     => 'language',
                               		       'type'     => 'string',
                               			   'required' => false,
                               			   'default'  => false
                               	)
                                )
);

$FunctionList['list_count'] = array( 'name' => 'list_count',
                                     'operation_types' => array( 'read' ),
                                     'call_method' => array( 'class' => 'SimpleForumCollection',
                                                             'method' => 'fetchTopicCount' ),
                                     'parameter_type' => 'standard',
                                     'parameters' => array(
                                        array( 'name'     => 'forum_node_id',
                                                'type'     => 'integer',
                                                'required' => true,
                                                'default'  => 1
                                                ),
                                        array( 'name'     => 'depth',
                                                'type'     => 'integer',
                                                'required' => false,
                                                'default'  => 1
                                        ),
                                        array( 'name'     => 'attribute_filter',
                                                'type'     => 'array',
                                                'required' => false,
                                                'default'  => array()
                                        ),
                                     	array( 'name'     => 'limitation',
                                     			'type'     => 'array',
                                     			'required' => false,
                                     			'default'  => false
                                    ),
                                   	    array( 'name'     => 'language',
                                   		        'type'     => 'string',
                                   		        'required' => false,
                                   			    'default'  => false
                                   	)
                                    ) 
);

$FunctionList['search'] = array( 'name' => 'search',
                                 'operation_types' => array( 'read' ),
                                 'call_method' => array( 
                                        'class' => 'SimpleForumCollection',
                                        'method' => 'searchTopic' ),
                                'parameter_type' => 'standard',
                                'parameters' => array(
                                        array( 'name'     => 'query',
                                               'type'     => 'string',
                                               'required' => false,
                                               'default'  => null
                                        ),
                                        array( 'name'     => 'forum_node_id',
                                               'type'     => 'integer',
                                               'required' => false,
                                               'default'  => null
                                        ),
                                        array( 'name'     => 'limit',
                                               'type'     => 'integer',
                                               'required' => false,
                                               'default'  => 10
                                        ),
                                        array( 'name'     => 'offset',
                                               'type'     => 'integer',
                                               'required' => false,
                                               'default'  => 0
                                        ),
                                        array( 'name'     => 'sort_by',
                                               'type'     => 'array',
                                               'required' => false,
                                               'default'  => array()
                                        ),
                                        array( 'name'     => 'attribute_filter',
                                               'type'     => 'array',
                                               'required' => false,
                                               'default'  => array()
                                        ),
                                   	    array( 'name'     => 'language',
                                   		        'type'     => 'string',
                                   		        'required' => false,
                                   			    'default'  => false
                                   	)
                                )
);

?>
