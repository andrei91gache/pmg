<?php
/**
 * Created by PhpStorm.
 * User: Andrei Gache
 * Date: 07/11/18
 * Time: 16:13
 */

namespace ModuleGenerator;

use ModuleGenerator\core\Client;

require 'vendor/autoload.php';

class GeneratorService
{
    private $configuration;

    public function __construct($type, $tab, $moduleName, $displayName, $description, $author, $version, $database, $instanceTab, $alertService, $cron, $form)
    {
        $this->configuration = [$type, $tab, $moduleName, $displayName, $description, $author, $version, $database, $instanceTab, $alertService, $cron, $form];
    }

    public function generateModule()
    {
        $client = new Client($this->configuration);
        $client->createModule();
    }
}