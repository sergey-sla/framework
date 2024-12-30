<?php

namespace Mirage\Smarty;

use \Smarty\Extension\Base;
use \Mirage\App;

class PluginsExtension extends Base {

    public function getModifierCallback(string $modifierName)
    {
        switch ($modifierName) {
            case 'declension': return [$this, 'smarty_modifier_decl'];
            case 'format_phone': return [$this, 'smarty_modifier_format_phone'];
            case 'has_role': return [$this, 'smarty_modifier_has_role'];
            case 't': return [$this, 'smarty_modifier_t'];
            case 'ucfirst': return [$this, 'smarty_modifier_ucfirst'];
        }
        return null;
    }


    /**
    * Smarty declension modifier plugin
    *
    * Type:     modifier<br>
    * Name:     declension<br>
    * Purpose:  simple declension
    *
    * @author Sergey Sla <ahtixpect@gmail.com>
    * @param integer $digit integer number to decl
    * @param array $expr array of words
    * @param boolean $onlyword if true return only word without number, default false
    * @return string
    */
    public function smarty_modifier_decl($digit, $expr, $onlyword=false)
    {
        return \Mirage\Helper::declension($digit, $expr, $onlyword);
    }


    /**
     * Smarty declension modifier plugin
     *
     * Type:     modifier<br>
     * Name:     format_phone<br>
     * Purpose:  format a 10-digit phone number
     *
     * @author Sergey Sla <ahtixpect@gmail.com>
     * @param integer $number phone number
     * @param string $format format
     * @return string
     */
    public function smarty_modifier_format_phone($number, $format="%s (%s) %s-%s-%s")
    {
        $original = $number;
        $number = preg_replace("/\D/","",$number);

        if (strlen($number) != 12 && strlen($number) != 11) return $original;

        if (substr($number,0,1) == '7') {
            $res = '+'.sprintf(
                    $format,
                    substr($number,0,1),
                    substr($number,1,3),
                    substr($number,4,3),
                    substr($number,7,2),
                    substr($number,9,2)
                );
        }
        elseif (substr($number,0,2) == '38') {
            $res = '+'.sprintf(
                    $format,
                    substr($number,0,2),
                    substr($number,2,3),
                    substr($number,5,3),
                    substr($number,8,2),
                    substr($number,10,2)
                );
        }
        else {
            return $original;
        }

        return $res;
    }


    /**
    * Smarty Auth::has_role modifier plugin
    *
    * Type:     modifier<br>
    * Name:     translate<br>
    * Purpose:  simple translate
    *
    * @author Galych Vitaliy <galych.vitaliy@gmail.com>
    * @param string $string  input string
    * @param string/array $params params to replace in text
    * @return string
    */
    public function smarty_modifier_has_role($string, array $replace = array(), $override_lang = false)
    {
        return \Mirage\Auth::hasRole($string);
    }


    /**
    * Smarty translate modifier plugin
    *
    * Type:     modifier<br>
    * Name:     translate<br>
    * Purpose:  simple translate
    *
    * @author Galych Vitaliy <galych.vitaliy@gmail.com>
    * @param string $string  input string
    * @param string/array $params params to replace in text
    * @return string
    */
    function smarty_modifier_t($string, array $replace = array(), $override_lang = false)
    {
        $lang = $override_lang ? $override_lang : App::get('lang');
        list($file, $name) = explode(".", $string);

        $path = (App::get('lang_path') ? App::get('lang_path') : (App::get('root_dir'). "/template/".App::get('layout')."/lang/"))."{$lang}/{$file}.inc";

        if (!empty($file) && file_exists($path)) {
            $lines = require($path);
            $line = !empty($lines[$name]) ? $lines[$name] : "+";

            foreach ($replace as $key => $value) {
                $line = str_replace(':'.$key, $value, $line);
            }

            return $line;
        }

        return '-';
    }


    /**
    * Smarty ucfirstmodifier plugin
    *
    * Type:     modifier<br>
    * Name:     ucfirst<br>
    * Purpose:  ucfirst first word in the string
    *
    * {@internal {$string|ucfirst} is the fastest option for MBString enabled systems }}
    *
    * @return string capitalized string
    * @author Sergey Slabak
    */
    function smarty_modifier_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }
}