<?php

return [
    'controllers' => [
        'invokables' => [
            'Template' => \Newage\Generators\Controller\TemplateController::class
        ]
    ],
    'console' => [
        'router' => [
            'routes' => [
                'generate-template' => [
                    'options' => [
                        'route' => 'generate template <template> <destination> --namespace= --name=',
                        'defaults' => [
                            'controller' => 'Template',
                            'action' => 'generate',
                        ]
                    ]
                ]
            ]
        ]
    ],
    'generators' => [
        'path' => '/templates/',
        'templates' => [
            'controller' => 'Controller/$NAME$Controller.stub',
            'model' => 'Model/$NAME$Model.stub',
            'table' => 'Model/$NAME$Table.stub',
            'service' => [
                'Service/$NAME$Service.stub',
                'Service/$NAME$ServiceInterface.stub',
            ],
            'factory-table' => 'Factory/$NAME$TableFactory.stub',
            'module' => [
                'model',
                'table',
                'service',
                'factory-table',
            ]
        ]
    ]
];
