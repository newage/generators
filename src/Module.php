<?php

namespace Newage\Generators;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

/**
 *
 * @author     V.Leontiev <vadim.leontiev@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT
 * @since      php 5.5 or higher
 * @see        https://github.com/newage/generators
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ConsoleUsageProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * Create documentation for console usage that module
     *
     * @param Console $console
     * @return array|null|string
     */
    public function getConsoleUsage(Console $console)
    {
        $docs = [
            'Code generate:',
            'generate template <template> <destination> <variables>' => 'Generate code from a template',
            ['<template>', 'A template name from a config'],
            ['<destination>', 'A destination path for a generated code'],
            ['<variables>', 'Variables for template. name=Class:namespace=My'],
        ];
        return $docs;
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        $config = require_once __DIR__ . '/config/module.config.php';
        return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
