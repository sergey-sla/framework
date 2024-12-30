<?php

namespace Mirage;

use \Smarty\Smarty;

class Tpl {

    public Smarty $smarty;

    function __construct($layout = "default")
    {
        $this->init($layout);
    }

    private function init($layout): void
    {
        $smarty = new Smarty();

        $smarty->setTemplateDir(App::get('root_dir')."/template/$layout/tpl/");
        $smarty->setCompileDir(App::get('runtime_dir')."/smarty");
        $smarty->setCacheDir(App::get('runtime_dir')."/smarty_cache");
        $smarty->setConfigDir(App::get('runtime_dir')."/smarty_configs");
        $smarty->error_reporting	=  E_ALL & ~E_NOTICE;

        $smarty->muteUndefinedOrNullWarnings();
        $smarty->addExtension(new \Mirage\Smarty\PluginsExtension());
        //$smarty->addPluginsDir(__DIR__.'/Smarty/plugins');

        if (Config::get('web.dev')) {
            $smarty->force_compile = true;
            $smarty->setCaching(Smarty::CACHING_OFF);
            $smarty->assign("dev", true);
        } else {
            $smarty->setCompileCheck(Smarty::COMPILECHECK_OFF);
        }

        $this->smarty = $smarty;
        //$this->registerPluginsDir();
    }

    public function registerPluginsDir($plugins_dir = __DIR__.'/Smarty/plugins'): void
    {
        $plugins = glob($plugins_dir.'/*.php');
        if (!empty($plugins)) {
            foreach ($plugins as $plugin) {
                require_once $plugin;
                list($type, $fn_name) = explode('.', basename($plugin, '.php'));
                $fn = 'smarty_'.$type.'_'.$fn_name;
                $allowed_types = [Smarty::PLUGIN_FUNCTION, Smarty::PLUGIN_MODIFIER, Smarty::PLUGIN_BLOCK, Smarty::PLUGIN_COMPILER, Smarty::PLUGIN_MODIFIERCOMPILER];
                if (in_array($type, $allowed_types) && is_callable($fn)) {
                    $this->smarty->registerPlugin($type, $fn_name, $fn);
                }
            }
        }
    }

}