<?php

namespace Framework\Module;

use Framework\Http\Request;
use Framework\Config\Config;

class Module
{
    private $type; 
    private $name; 
    private $config;
    private $request;

    public function __construct(Request $request, Config $config)
    {
        $this->config = $config;
        $this->request = $request;

        $this->setModuleType();   
        $this->setModuleName();
    }

    public function isDiscovered()
    {
        return $this->name !== null;
    }

    public function getModuleName()
    {
        return $this->name;  
    }

    public function getModuleType()
    {
        return $this->type;  
    }
    
    public function getModulePath()
    {
        return DIR_MODULES . '/' . $this->name;  
    }

    public function getModuleRoutesFilePath()
    {
        $routesFile = $this->getModulePath() . '/routes/' . $this->type . '.php';

        if(file_exists($routesFile)) {
            return $routesFile;
        }

        throw new \Exception('Could not find routes for module: ' . $this->name);
    }

    public function getModuleConfiguration()
    {
        $configFile = $this->getModulePath() . '/config.php';

        if(file_exists($configFile)) {
            $this->configuration = require_once($configFile);
        }

        throw new \Exception('Could not find configuration for module: ' . $this->name);
    }

    public function getActiveModules()
    {
        return $this->config->default['modules'];
    }

    private function setModuleType()
    {
        $this->type = 'frontend';

        if($this->request->segments(0) == $this->config->default['url']['admin_route_prefix']) {
            $this->type = 'backend';
        } elseif($this->request->segments(0) == $this->config->default['url']['api_route_prefix']) {
            $this->type = 'api';
        } 
    }

    private function setModuleName()
    {
        if($this->type == 'api' || $this->type == 'backend') {
            $segment = $this->request->segments(1);
        } else {
            $segment = $this->request->segments(0);
        }

        $modules = $this->config->default['modules'];
        $this->name = $modules['/' . $segment] ?? null;
    }
}