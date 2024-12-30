<?php
/**
 * Created by PhpStorm.
 * User: Виталий
 * Date: 25.12.2014
 * Time: 11:43
 */

namespace Mirage;

/**
 * GUMP - A fast, extensible PHP input validation class
 *
 * @author      Sean Nieuwoudt (http://twitter.com/SeanNieuwoudt)
 * @copyright   Copyright (c) 2014 Wixelhq.com
 * @link        http://github.com/Wixel/GUMP
 * @version     1.0
 */

class Validator extends \GUMP
{
    public function __construct($lang)
    {
        parent::__construct($lang);
    }
}