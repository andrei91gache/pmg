<?php
/**
 * Created by PhpStorm.
 * User: Andrei Gache
 * Date: 07/11/18
 * Time: 16:06
 */

header('Content-Type: application/x-www-form-urlencoded');

require 'engine/GeneratorService.php';


$type = 'Module';
$tab = 'Administration';

$data = json_decode($_POST['x'], true);

$moduleName = $data['moduleName'];
$displayName =  $data['moduleDisplayName'];
$description = $data['moduleDescription'];
$author = $data['moduleAuthor'];
$version =  $data['moduleVersion'];
$database = $data['databases'];
$alertService =  $data['moduleAlertNotif'];
$cron = $data['moduleCron'];
$forms = $data['forms'];

///a voir
$instanceTab = false;






$service = new \ModuleGenerator\GeneratorService($type, $tab, $moduleName, $displayName, $description, $author, $version, $database, $instanceTab, $alertService, $cron, $forms);
$service->generateModule();


if ($data !== null){
    $result = array(
        "success" => true,
        "fileName" =>  $moduleName . '.zip'
    );

    echo json_encode($result);

}

