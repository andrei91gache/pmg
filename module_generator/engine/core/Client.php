<?php
/**
 * Created by PhpStorm.
 * User: Andrei Gache
 * Date: 07/11/18
 * Time: 16:09
 */

namespace ModuleGenerator\core;


use ModuleGenerator\core\BO\DirectoryCreator;
use ModuleGenerator\core\BO\FileHydrator;
use ModuleGenerator\core\BO\ModuleGeneratorObject;
use ModuleGenerator\core\BO\ZipCreator;

class Client extends ModuleGeneratorObject
{

    public function __construct($configuration)
    {
        parent::__construct($configuration);
    }

    public function createModule()
    {
        $dir = new DirectoryCreator($this->getConfig());
        $dir->createDir();

        $files=  new FileHydrator($this->getConfig());

        $files->createFiles();

        $zip = new ZipCreator($this->getConfig());
        $zip->createZipModule();
    }
}