<?php
/**
 * Created by PhpStorm.
 * User: Andrei Gache
 * Date: 08/11/18
 * Time: 15:00
 */

namespace ModuleGenerator\core\BO;


class ZipCreator extends ModuleGeneratorObject
{


    /**
     * ZipCreator constructor.
     */
    public function __construct($configuration)
    {
        parent::__construct($configuration);
    }

    public function createZipModule()
    {
        $dir = getcwd()."/modules_generated/";
        // Get real path for our folder
        $rootPath = realpath($dir.$this->moduleName);

        // Initialize archive object
        $zip = new \ZipArchive();
        $zip->open($dir.$this->moduleName.'.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {


                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }else{
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addEmptyDir($relativePath);
            }

        }

        // Zip archive will be created only after closing object
        $zip->close();
    }
}