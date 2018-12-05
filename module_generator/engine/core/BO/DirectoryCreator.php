<?php
/**
 * Created by PhpStorm.
 * User: Andrei Gache
 * Date: 08/11/18
 * Time: 14:39
 */

namespace ModuleGenerator\core\BO;


class DirectoryCreator extends ModuleGeneratorObject
{

    /**
     * DirectoryCreator constructor.
     */
    public function __construct($configuration)
    {
        parent::__construct($configuration);
    }


    public function createDir()
    {
        $dir = getcwd()."/modules_generated/";

        $module_name = $dir.$this->moduleName."/".$this->moduleName;

        //create principal dir with name module
        mkdir($module_name, 0777, true);

        mkdir($module_name."/views/templates/admin", 0777, true);

        if($this->database){
            mkdir($module_name."/sql", 0777, true);
        }
        //Creer affichage pour alert notifications
        if ($this->alertService){
            mkdir($module_name."/views/js", 0777, true);
            mkdir($module_name."/views/css", 0777, true);

        }

        if ($this->cron){
            //Create src folder
            mkdir($module_name."/src/config", 0777, true);
            mkdir($module_name."/src/classes", 0777, true);
            //Create logs folder
            mkdir($module_name."/src/logs", 0777, true);
            //Create files folder
            mkdir($module_name."/src/files", 0777, true);

            //Create bin folder for cron
            mkdir($module_name."/bin", 0777, true);

        }

        if ($this->instanceTab){
            mkdir($module_name."/controllers/admin", 0777, true);

        }
    }
}