<?php
/**
 * Created by PhpStorm.
 * User: Виталий
 * Date: 11.01.2015
 * Time: 19:41
 */

namespace Mirage;

use \Mirage\App;

class Model {

    public $validator;
    public $rules = array();
    public $filters = array();
    public $errors = array();
    public $names = array();

    public function __construct()
    {
        $lang = App::get('lang') ?: 'en';
        $this->validator = new Validator($lang == 'ua' ? 'uk' : $lang);
        $this->validator->validation_rules($this->rules);
        $this->validator->filter_rules($this->filters);
        $this->validator->set_fields_error_messages($this->errors);
        $this->validator::set_field_names($this->names);
        $this->init();
    }

    public function init()
    {

    }

    public function validate($data)
    {
        return $this->validator->run($data);
    }

    public function errors()
    {
        return $this->validator->errors();
    }

    public function is_valid($data)
    {
        return $this->validator->validate($data, $this->rules) === TRUE ? 1 : false;
    }

    public function filter($data)
    {
        return $this->validator->filter($data, $this->filters);
    }

}