<?php 
/**
 * PrestaShop module created by Arturo
 *
 * @author    arturo https://artulance.com
 * @copyright 2020-2021 arturo
 * @license   This program is free software but you can't resell 
 *
 * CONTACT WITH DEVELOPER
 * artudevweb@gmail.com
 */
class holi extends Module{
    public function __construct()
    {
        $this->name          = 'holi';
        $this->tab           = 'Blocks';
        $this->author        = 'artulance.com';
        $this->version       = '1.0.0';
        $this->bootstrap     = true;
    
        //le indicamos que lo construya
        parent::__construct();
        $this->displayName = $this->l('Holi en la principal');
        $this->description = $this->l('Este modulo solo saluda en la home y antes del footer');

    }

       /* Como comprobación si no está instalado o si esta registrado en el hook de la home o en el hook del footer,devolverá false */
    public function install()
    {
        if(!parent::install() 
        || ! $this->registerHook('displayHome') 
        || !$this->registerHook('displayFooterBefore')
        || !$this->instalarconfiguraciones())
        {
            return false;
        }else{
            //si esta bien instalado nos dirá que es true
            return true;
        }
    }
    /* Como comprobación si no está desinstalado o si esta registrado en el hook de la home o en el hook del footer,devolverá false */
    public function unistall()
    {
        if(!parent::unistall() 
        || ! $this->unregisterHook('displayHome') 
        || !$this->unregisterHook('displayFooterBefore')
        || !$this->desininstalarconfiguraciones() )
        { 
            return false;
        }else{
            return true;
        }
    }

    /********Función no necesaria pero para dejar algo puesto y si acaso limpiar la base de datos después de desinstalar ******/
    function instalarconfiguraciones()
    {
        //Hago los campos en la base de datos
        $langs= Language::getLanguages();
            foreach ($langs as $lang) {
                $texto_header = "";
                //$texto_header=$this->l("Texto predeterminado, si lo pongo con esto luego es traducible en prestashop");
                Configuration::updateValue('HOLI_MODULO_TEXTO_HOME_'.$lang['id_lang'], $texto_header);
            }
            return true;
    }
    function desininstalarconfiguraciones()
    {
        //Los quito usando un método de la clase configuration.php
        $langs= Language::getLanguages();
            foreach ($langs as $lang) {
                Configuration::deteleByName('HOLI_MODULO_TEXTO_HOME_'.$lang['id_lang']);
            }
            return true;
    }

    /* Función que solo es necesaria si hay algo que configurar del módulo, en este caso si */
    public function getContent()
    {
        return $this->postProcess() . $this->getForm();
    }

    public function getForm()
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->context->controller->getLanguages();
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $this->context->controller->default_form_language;
        $helper->allow_employee_form_lang = $this->context->controller->allow_employee_form_lang;
        $helper->title = $this->displayName;

        /*Puedo configurar cual va a ser mi campo de submit */
        $helper->submit_action = 'holi';
        //conseguimos las lenguas que hay en clases language
        /* Hacer esto solo si tenemos un idioma más en la tienda, si no, no aparecerá el selector cuando cambiemos a true en el array de input */
        $langs= Language::getLanguages();
        //print_r($langs); 
        foreach ($langs as $lang) {
            //$id=$lang['id_lang'];
            $helper->fields_value['texto_header'][$lang['id_lang']] = Configuration::get('HOLI_MODULO_TEXTO_HOME_'.$lang['id_lang']);
        }
       
        /*Asocio que cada campo tenga su valor correspondiente */
        $helper->fields_value['texto_footer'] = Configuration::get('HOLI_MODULO_TEXTO_FOOTER');
        
/* En este caso hago 2 formularios por separado y los separo en una variable form[], pero puedo igualar todo a una variable definida como array y hacer un return, también
puedo poner seguido del input, otro array de input y hacerlo en un mismo bloque pero esto será en otro módulo*/

        $this->form[0] = array(
            'form' => array(
                'legend' => array(
                   /* 'title' => $this->displayName*/
                    'title' => $this->l('Texto en la principal ')
                 ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Texto de la home'),
                        'desc' => $this->l('Qué texto quieres que aparezca en la página de inicio'),
                        'hint' => $this->l('Este texto solo se verá en la home'),
                        'name' => 'texto_header',
                        'lang' => true,
                     ),
                 ),
                'submit' => array(
                    'title' => $this->l('Save')
                 )
             )
         );
        $this->form[1] = array(
            'form' => array(
                'legend' => array(
                   /* 'title' => $this->displayName*/
                   'title' => $this->l('Texto en el footer')
                 ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Texto del footer'),
                        'desc' => $this->l('Qué texto quieres que aparezca en el footer'),
                        'hint' => $this->l('Este texto se verá antes del footer'),
                        'name' => 'texto_footer',
                        'lang' => false,
                     ),
                 ),
                'submit' => array(
                    'title' => $this->l('Save')
                 )
             )
         );
        return $helper->generateForm($this->form,$this->form[1] );
    }

    public function postProcess()
    {
        /* El submit con lo que hayamos configurado el campo en el getform */
        if (Tools::isSubmit('holi')) {
            $langs= Language::getLanguages();
            foreach ($langs as $lang) {
                //$id=$lang['id_lang'];
                //lo renombro con _ porque automaticamente prestashop si es multilingue es nombreinput_ididioma
                $texto_header = Tools::getValue('texto_header_'.$lang['id_lang']);
                Configuration::updateValue('HOLI_MODULO_TEXTO_HOME_'.$lang['id_lang'], $texto_header);
            }


           
            $texto_footer = Tools::getValue('texto_footer');
        /* Cogemos el texto de la tabla ps_configuration con su campo correspondiente para poner en el value*/
           
            Configuration::updateValue('HOLI_MODULO_TEXTO_FOOTER', $texto_footer);
            /* Devuelvo un mensaje de confirmación si se actualiza adecuadamente */
            return $this->displayConfirmation($this->l('Updated Successfully'));
        }
    }

  
    public function hookdisplayHome()
    {
        $id= $this->context->language->id;
        $texto_header = Configuration::get('HOLI_MODULO_TEXTO_HOME_'.$id);
        $this->context->smarty->assign(array(
            'texto_variable' => $texto_header,
        ));
        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/home.tpl');
    }

    public function hookdisplayFooterBefore()
    {
        
        $texto_footer = Configuration::get('HOLI_MODULO_TEXTO_FOOTER');
        $this->context->smarty->assign(array(
            'texto_variable_footer' => $texto_footer,
        ));
        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/footer.tpl');
    }

    public function hookDisplayHeader()
    {

      /*  Si queremos estilos propios podemos adjuntar el css en el header, pero si no queremos que se pongan en todas las páginas
      en este caso, solo queremos que salga en la home, pregunto si la página es el index, si lo es, entonces adjunta los estilos
      
      if(!isset($this->context->controller->php_self) && $this->context->controller->php_self=='index'){
            $this->context->controller->addCSS($this->local_path.'views/templates/css/estilos.css');
            $this->context->controller->addJS($this->local_path.'views/templates/js/script.js');
        }*/
    }

}



?>