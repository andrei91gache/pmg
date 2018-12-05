<?php
/**
 * Created by PhpStorm.
 * User: Andrei Gache
 * Date: 08/11/18
 * Time: 11:15
 */

namespace ModuleGenerator\core\BO;

abstract class ModuleGeneratorObject
{
    private $configuration =  array();
    protected $type, $tab, $moduleName, $displayName, $description, $author, $version, $database, $instanceTab, $alertService, $cron, $form;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
        list($type, $tab, $moduleName, $displayName, $description, $author, $version, $database, $instanceTab, $alertService, $cron, $form) = $configuration;
        $this->type = $type;
        $this->tab = $tab;
        $this->moduleName = $moduleName;
        $this->displayName = $displayName;
        $this->description = $description;
        $this->author = $author;
        $this->version = $version;
        $this->database = $database;
        if ($instanceTab === true){
            $this->instanceTab = 1;
        }else{
            $this->instanceTab = 0;
        }
        $this->alertService = $alertService;
        $this->cron = $cron;
        $this->form = $form;
    }

    protected function getConfig()
    {
        return $this->configuration;
    }
}