<?php

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
                                        )
                                    ) 
);

?>
