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
                        'route' => 'generate template <template> <destination> <variables>',
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
        'templates' => [
            'controller' => '/templates/Controller/$NAME$Controller.stub',
            'model' => '/templates/Model/$NAME$Model.stub',
            'table' => '/templates/Model/$NAME$Table.stub',
            'service' => '/templates/Service/$NAME$Service.stub',
            'factory-table' => '/templates/Factory/$NAME$TableFactory.stub',
            'module' => [
                '/templates/Model/$NAME$Model.stub',
                '/templates/Model/$NAME$Table.stub',
                '/templates/Service/$NAME$Service.stub',
                '/templates/Factory/$NAME$TableFactory.stub',
            ]
        ]
    ]
];
