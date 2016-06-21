<?php
namespace c3p0\tools;

class Validator {
    
    const NOT_IS_NULL = 'not_is_null';
    const BETWEEN_AND = 'between_and';
    const IS_QQ = 'is_qq';
    const IS_MAIL = 'is_mail';
    const IS_MOBILE = 'is_mobile'; 
    const REGULAR_EXPRESSION = 'regular_expression'; 
    
    private $lists = array();
    private $errorInfos = array(); 
    
    public function add($field, $handler, $errMessage = '', $options = array()) {
        array_push($this->lists, array(
            'field' => $field,
            'handler' => $handler,
            'error' => $errMessage,
            'options' => $options,
        ));
        return $this; 
    }
    
    public function doValid() {
        if ($this->lists) {
            foreach ($this->lists as $item) {
                $handler = $item['handler'];
                $error = $item['error'];
                $result = call_user_func_array(array('\c3p0\tools\Validator', $handler), array($item['field'], $item['options']));
                if (!$result) {
                    array_push($this->errorInfos, $error); 
                }
            }
        }
    }
    
    public function getErrorInfo() {
        return $this->errorInfos; 
    }
    
    public static function not_is_null($field, $options = array()) {
        return !empty($field); 
    }
    
    public static function between_and($field, $options = array()) {
        $len = strlen($field);
        $result = $len >= $options['min'] && $len <= $options['max']; 
        return $result; 
    }
    
    public static function is_qq($field, $options = array()) {
        return preg_match('/^[1-9]\d{4,10}$/', $field); 
    }
    
    public static function is_mail($field, $options = array()) {
        return preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $field); 
    }
    
    public static function is_mobile($field, $options = array()) {
        return preg_match('/^1[34578]{1}\d{9}$/', $field); 
    }
    
    public static function regular_expression($field, $options = array()) {
        if (empty($options['expression'])) return false; 
        return preg_match($options['expression'], $field); 
    }
    
}
