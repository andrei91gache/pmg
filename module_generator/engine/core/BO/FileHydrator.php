<?php
/**
 * Created by PhpStorm.
 * User: Andrei Gache
 * Date: 08/11/18
 * Time: 14:53
 */


namespace ModuleGenerator\core\BO;

require __DIR__.'/../../vendor/autoload.php';

use Nette;
class FileHydrator extends ModuleGeneratorObject
{
    /**
     * FileHydrator constructor.
     */
    public function __construct($configuration)
    {

        parent::__construct($configuration);
    }

    public function createFiles()
    {

        $this->generateXMLConfigFile();
        $this->generatePredefinitedFiles();
        if ($this->alertService){
            $this->createAlertTemplateFile();
        }

        $this->generatePhpModuleFile();
    }

    private function createAlertTemplateFile()
    {
        $dir = getcwd()."/modules_generated/";
        $module_name = $dir.$this->moduleName.'/'.$this->moduleName;

        $result = file_get_contents(getcwd().'/engine/files/alert_template.tpl');
        $result = str_replace('VAR', $this->moduleName.'_permissions', $result);
        file_put_contents($module_name.'/views/templates/admin/alert_template.tpl', $result);
    }

    private function generateXMLConfigFile()
    {
       $dir = getcwd()."/modules_generated/";
        $module_name = $dir.$this->moduleName.'/'.$this->moduleName;
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<module>
	<name>$this->moduleName</name>
	<displayName><![CDATA[$this->displayName]]></displayName>
	<version><![CDATA[$this->version]]></version>
	<description><![CDATA[$this->description]]></description>
	<author><![CDATA[$this->author]]></author>
	<tab><![CDATA[$this->tab]]></tab>
	<is_configurable>1</is_configurable>
	<need_instance>1</need_instance>
</module>";
        file_put_contents($module_name.'/config.xml', $xml);

    }

    private function generatePhpModuleFile()
    {
        $dir = getcwd()."/modules_generated/";
        $module_name = $dir.$this->moduleName.'/'.$this->moduleName;
        $class = new Nette\PhpGenerator\ClassType(ucfirst($this->moduleName));
        $class->setExtends('Module');
        $method = $class->addMethod('__construct');
        $method->addBody('$this->name = ?; ' , [$this->moduleName]);
        $method->addBody('$this->tab = ? ;' , [$this->tab]);
        $method->addBody('$this->version = ? ;' , [$this->version]);
        $method->addBody('$this->author = ? ;' , [$this->author]);
        $method->addBody('$this->need_instance = ?;' , [$this->instanceTab]);
        $method->addBody('$this->bootstrap = true;');
        $method->addBody('parent::__construct();');
        $method->addBody('$this->displayName = ?;', [$this->displayName]);
        $method->addBody('$this->description = ?;', [$this->description]);


        $methodInstall = $class->addMethod('install');


        $methodUninstall = $class->addMethod('uninstall');
        $methodUninstall->addBody('return parent::uninstall();');


        $method = $class->addMethod('getContent');
        if ($this->alertService){
            $methodInstall->addBody('$this->context->controller->addJS($this->_path.\'/views/js/magicsuggest-min.js\');');
            $methodInstall->addBody('$this->context->controller->addCSS($this->_path.\'/views/css/magicsuggest-min.css\');');
            $methodInstall->addBody('return parent::install();');

            $alert_form =     //Deuxieme formulaire
                array(
                    'form_name' =>  'Settings',
                    'inputs' =>  array(
                        array(
                            'name'=>'subject',
                            'display_name'=>'Subjet',
                            'description'=>'',
                            'placeholder'=>''
                        ),
                        array(
                            'name'=>'message',
                            'display_name'=>'Message',
                            'description'=> '',
                            'placeholder'=>''
                        ),
                    ),
                );
            array_unshift($this->form, $alert_form);

            $method->addBody(' global $cookie;');
            $method->addBody('$output = \'\';');
            $method->addBody('$output .= $this->_preProcess();');
            $method->addBody('$cookie = new Cookie(\'psAdmin\');');
            $method->addBody('$employee = new Employee((int)$cookie->id_employee);');
            $method->addBody('$getProfileUser =  Profile::getProfile($employee->id_profile)[\'name\'];');
            $method->addBody('$getUsers   =  Db::getInstance()->executeS("
            SELECT `id_employee`, CONCAT(firstname ,\' \' , lastname) AS fullname
            FROM `"._DB_PREFIX_."employee`
            WHERE `active` = 1
            ORDER BY `lastname` ASC
        ");');
            $method->addBody('$getConfigEmail = json_encode(unserialize(Configuration::get(\''.strtoupper($this->moduleName).'_ACCESS_EMAIL\', \'\')));');
            $method->addBody('$getConfigSms = json_encode(unserialize(Configuration::get(\''.strtoupper($this->moduleName).'_ACCESS_SMS\', \'\')));');
            $method->addBody('$getConfigHangouts = json_encode(unserialize(Configuration::get(\''.strtoupper($this->moduleName).'_ACCESS_HANGOUTS\', \'\')));');
            $method->addBody('$this->context->smarty->assign(
            array(
                \'getUsersToTpl\' => $getUsers,
                \'getUsers\' => json_encode($getUsers),
                \'getProfileUser\' => $getProfileUser,
                \'getConfigEmail\' => $getConfigEmail,
                \'getConfigSms\'  => $getConfigSms,
                \'getConfigHangouts\' => $getConfigHangouts
            )
        );');
            $method->addBody('$this->context->smarty->assign(\'module_dir\', _PS_ROOT_DIR_ . $this->_path);');
            $method->addBody('$output .= $this->context->smarty->fetch($this->local_path . \'views/templates/admin/infos.tpl\');');
            $method->addBody('$output = $this->context->smarty->fetch($this->local_path . \'views/templates/admin/alert_template.tpl\');');


            $preProcessMethod = $class->addMethod('_preProcess');
            $preProcessMethod->addBody('
        if (Tools::isSubmit($this->name . \'_permissions\')) {
        
            $permission_employees_email = serialize(Tools::getValue(\'permission-email\'));
            $permission_employees_hangouts = serialize(Tools::getValue(\'permission-hangouts\'));
            $permission_employees_sms = serialize(Tools::getValue(\'permission-sms\'));

            Configuration::updateValue(\''.strtoupper($this->moduleName).'_ACCESS_EMAIL\', $permission_employees_email, true);
            Configuration::updateValue(\''.strtoupper($this->moduleName).'_ACCESS_HANGOUTS\', $permission_employees_hangouts, true);
            Configuration::updateValue(\''.strtoupper($this->moduleName).'_ACCESS_SMS\', $permission_employees_sms, true);
}');

        }else{
            $methodInstall->addBody('return parent::install();');
            $method->addBody('$this->context->smarty->assign(\'module_dir\', $this->_path);');
            $method->addBody('$output = $this->context->smarty->fetch($this->local_path.\'views/templates/admin/infos.tpl\');');
        }



        if (count($this->form)>0){
            $method->addBody(
                'if (((bool)Tools::isSubmit(?)) == true) {
    $this->postProcess();
}', ["submit".ucfirst($this->moduleName)."Module"]);
        }
        //Generation de formulaire
        $this->createForm($class,$method);

        $php_begin = "<?php\n";
        file_put_contents($module_name.'/'.$this->moduleName.'.php', $php_begin.$class);
    }

    private function generatePredefinitedFiles()
    {
       $dir = getcwd()."/modules_generated/";
        $module_name = $dir.$this->moduleName.'/'.$this->moduleName;
        copy ( getcwd().'/engine/files/phpunit.xml', $module_name.'/phpunit.xml' );
        copy ( getcwd().'/engine/files/index.php', $module_name.'/index.php' );
        copy ( getcwd().'/engine/files/composer.json', $module_name.'/composer.json' );

        copy ( getcwd().'/engine/files/index.php', $module_name.'/views/templates/index.php' );
        copy ( getcwd().'/engine/files/index.php', $module_name.'/views/templates/admin/index.php' );
        copy ( getcwd().'/engine/files/configure.tpl', $module_name.'/views/templates/admin/infos.tpl' );

        if ($this->alertService){
            copy ( getcwd().'/engine/files/magicsuggest-min.css', $module_name.'/views/css/magicsuggest-min.css' );
            copy ( getcwd().'/engine/files/magicsuggest-min.js', $module_name.'/views/js/magicsuggest-min.js' );
        }
        if ($this->cron){
            copy ( getcwd().'/engine/files/cron.php', $module_name.'/bin/cron.php' );
        }
        //copy ( getcwd().'/engine/files/index.php', getcwd().'/'.$this->moduleName.'/controllers/admin/index.php' );
    }

    private function createForm($class, $method){

        if (count($this->form) > 0){

            $methodRenderForm = $class->addMethod('renderForm');
            $methodRenderForm->addComment('Cette methode elle vas recuperer les formulaires et vas les afficher');
            $methodRenderForm->addBody('$helper = new HelperForm();');
            $methodRenderForm->addBody('$helper->show_toolbar = false;');
            $methodRenderForm->addBody('$helper->table = $this->table;');
            $methodRenderForm->addBody('$helper->module = $this;');
            $methodRenderForm->addBody('$helper->default_form_language = $this->context->language->id;');
            $methodRenderForm->addBody('$helper->allow_employee_form_lang = Configuration::get(\'PS_BO_ALLOW_EMPLOYEE_FORM_LANG\', 0);');
            $methodRenderForm->addBody('$helper->identifier = $this->identifier;');
            $methodRenderForm->addBody('$helper->submit_action = ?;', ["submit".ucfirst($this->moduleName)."Module"]);
            $methodRenderForm->addBody('$helper->currentIndex = $this->context->link->getAdminLink(\'AdminModules\', false)
            .\'&configure=\'.$this->name.\'&tab_module=\'.$this->tab.\'&module_name=\'.$this->name;');
            $methodRenderForm->addBody('$helper->token = Tools::getAdminTokenLite(\'AdminModules\');');
            $methodRenderForm->addBody('$helper->tpl_vars = array(
            \'fields_value\' => $this->getConfigFormValues(), /* Add values for your inputs */
            \'languages\' => $this->context->controller->getLanguages(),
            \'id_language\' => $this->context->language->id,
        );');

            $inputs_names = [];
            $generatedMethods = array();
            foreach ($this->form as $form){

                $methodName = ucfirst(str_replace(' ', '',  $form['form_name']));
                $generatedMethods[] = '$this->getConfigForm'.$methodName.'()';
                $generatedMethod = $class->addMethod('getConfigForm'.$methodName);

                $inputs = [];
                foreach ($form['inputs'] as $input){
                    $inputs_names[] = strtoupper($this->moduleName."_".$input['name']);
                    $inputs[] = "array(
                        'col' => 4,
                        'type' => 'text',
                        'desc' => '".$input['description']."',
                        'name' => '".strtoupper($this->moduleName."_".$input['name'])."',
                        'label' => '".$input['display_name']."',
                    )";

                }
                $generatedMethod->addBody('
                        return array(
            \'form\' => array(
                \'legend\' => array(
                    \'title\' => \''.$form['form_name'].'\',
                    \'icon\' => \'icon-cogs\',
                ),
                \'input\' => array(
                    '.implode(',', $inputs).'
                ),
                \'submit\' => array(
                    \'title\' => $this->l(\'Save\'),
                ),
            ),
        );
                ');
            }

            $methodRenderForm->addBody('return $helper->generateForm(array( '.implode(",", $generatedMethods).') );');
            $method->addBody('return $output.$this->renderForm();');

            $getConfigFormValues = $class->addMethod('getConfigFormValues');
            $array_config_values = [];
            foreach ($inputs_names as $inputs_name){

                $array_config_values[] = "'$inputs_name' => Configuration::get('$inputs_name')";

            }
            $getConfigFormValues->addBody('
                    return array(
                    
                    '.implode(",\n", $array_config_values).'
);
            ');

            $postProcess = $class->addMethod('postProcess');
            $postProcess->addBody('$form_values = $this->getConfigFormValues();');
            $postProcess->addBody('foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
}');

        }else{
            $method->addBody('return $output;');
        }


    }
}