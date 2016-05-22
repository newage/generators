<?php
namespace Newage\Generators\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt;

class TemplateController extends AbstractActionController
{
    public function generateAction()
    {
        $request = $this->getRequest();

        $templateName = $request->getParam('template');
        $destination = getcwd() . $request->getParam('destination');
        $variables['NAMESPACE'] = ucfirst($request->getParam('namespace'));
        $variables['NAME'] = ucfirst($request->getParam('name'));

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

        $templates = $config['generators']['templates'];
        $this->getTemplatePath(
            $templateName,
            $templates,
            $console,
            $destination,
            $variables
        );
    }

    protected function getTemplatePath($templateName, $templates, $console, $destination, $variables)
    {
        if (array_key_exists($templateName, $templates)) {
            $templateName = $templates[$templateName];
        }
        if (is_array($templateName)) {
            foreach($templateName as $oneTemplate) {
                $this->getTemplatePath($oneTemplate, $templates, $console, $destination, $variables);
            }
        } else {
            $this->templateApply($templateName, $console, $destination, $variables);
        }
    }

    /**
     * @param $templateFromConfig
     * @param $console
     * @param $destination
     * @param $variables
     */
    protected function templateApply($templateFromConfig, $console, $destination, $variables)
    {
        $config = $this->getServiceLocator()->get('config');

        $templatePath = getcwd() . $config['generators']['path'] . $templateFromConfig;
        if (!file_exists($templatePath)) {
            $console->writeLine('Template file not exists: ' . $templatePath, color::RED);
            return;
        }

        $template = file_get_contents($templatePath);
        $destination .= '/' . substr($templateFromConfig, 0, -4) . 'php';
        foreach ($variables as $variableName => $variableValue) {
            $template = str_replace('$' . $variableName . '$', $variableValue, $template);
            //lower case variable
            $template = str_replace('&' . $variableName . '&', lcfirst($variableValue), $template);
            $destination = str_replace('$' . $variableName . '$', $variableValue, $destination);
        }

        $destinationDir = dirname($destination);
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }

        $rewrite = 'y';
        if (file_exists($destination)) {
            $rewrite = Prompt\Confirm::prompt('File is exists. Are you want to rewrite file? [y/n]', 'y', 'n');
        }

        if ($rewrite == 'y') {
            file_put_contents($destination, $template);
            $console->writeLine('The class generated: ' . $destination, color::GREEN);
        }
    }
}
