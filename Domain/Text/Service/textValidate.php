<?php

namespace Fab\Domain\Text\Service;

class textValidate
{
    public function __construct()
    {
        $this->allowedKeys = array(
                "id", 
                "key", 
                "content", 
                "language", 
                "category");
        
        $this->errors = array();
    }

    public function setValues($array) 
    {
        $this->array = $array;
    }
    
    public function validate()
    {                   
        $valid = true;
        foreach($this->allowedKeys as $key){
            $function = $key."Validate";
            $result = $this->$function($this->array[$key]);
            if($result == false){
                $valid = false;
            }
        }
        
        return $valid;
    }
    
    private function addError($key, $number, $array=false)
    {
        $this->errors[$key][$number]['error'] = 1;
        $this->errors[$key][$number]['options'] = $array;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getErrorsByKey($key)
    {
        return $this->errors[$key];
    }
    
    function idValidate($value){
        if(empty($value)){
            return true;
        }else{
            if(ctype_digit($value)){
                return true;
            }else{
                $this->addError("id", 6);
                return false;
            }
        }
    }
    
    public function keyValidate($value)
    {
        return $this->defaultValidation("key", $value, 255, true);
    }
    
    public function contentValidate($value)
    {
        return $this->defaultValidation("content", $value, 4000000, true);
    }
    
    public function languageValidate($value)
    {
        return $this->defaultValidation("language", $value, 2, true);
    }
    
    public function categoryValidate($value)
    {
        return $this->defaultValidation("category", $value, 255, true);
    }
    
    public function defaultValidation($key,$value,$length,$required = false)
    {
        $bool = true;
        
        if($required === true){
            $bool = $this->requiredValidation($key, $value);
        }
        
        if(strlen($value) > $length){
            $this->addError($key, 2, array("maxlength" => $length, "actuallength" => strlen($value)));
            $bool = false;
        }
        
        if($bool == false){
            return false;
        }
        return true;
    }
    
    public function requiredValidation($key, $value)
    {
        if($value == ""){
            $this->addError($key, 1);
            return false;
        }
        return true;
    }
    
    public function resetErrorKeyForTesting($key)
    {
        $this->errors[$key] = array();
    }
}