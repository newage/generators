<?php
namespace Newage\Generators\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\ColorInterface as Color;

class TemplateController extends AbstractActionController
{
    const PARAMETER_DELIMITER = ':';
    const VALUE_DELIMITER = '=';

    public function generateAction()
    {
        $request = $this->getRequest();

        $templateName = $request->getParam('template');
        $destination = getcwd() . $request->getParam('destination');
        $variables = $this->deserializeVariables($request->getParam('variables'));

        $config = $this->getServiceLocator()->get('config');
        $console = $this->getServiceLocator()->get('console');
        if (!isset($config['generators']['templates'][$templateName])) {
            $console->writeLine('Template not exists: '.$templateName, color::RED);
            return;
        }

        if (!is_dir($destination)) {
            $console->writeLine('Directory not exists: '.$destination, color::RED);
            return;
        }

        $templateFromConfig = $config['generators']['templates'][$templateName];
        if (is_array($templateFromConfig)) {
            foreach ($templateFromConfig as $oneTemplate) {
                $this->templateApply($oneTemplate, $console, $destination, $variables);
            }
        } else {
            $this->templateApply($templateFromConfig, $console, $destination, $variables);
        }
    }

    protected function deserializeVariables($string)
    {
        $values = [];
        $parameters = explode(self::PARAMETER_DELIMITER, $string);
        foreach ($parameters as $parameter) {
            $parts = explode(self::VALUE_DELIMITER, $parameter);
            $values[strtoupper($parts[0])] = $parts[1];
        }

        return $values;
    }

    /**
     * @param $templateFromConfig
     * @param $console
     * @param $destination
     * @param $variables
     */
    protected function templateApply($templateFromConfig, $console, $destination, $variables)
    {
        $templatePath = getcwd() . $templateFromConfig;
        if (!file_exists($templatePath)) {
            $console->writeLine('Template file not exists: ' . $templatePath, color::RED);
            return;
        }

        $template = file_get_contents($templatePath);
        $destination .= substr($templateFromConfig, 10, -4) . 'php';
        foreach ($variables as $variableName => $variableValue) {
            $template = str_replace('$' . $variableName . '$', $variableValue, $template);
            $destination = str_replace('$' . $variableName . '$', $variableValue, $destination);
        }

        $destinationDir = dirname($destination);
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }

        file_put_contents($destination, $template);
    }
}
