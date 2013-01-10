<?php

namespace Fab\Domain\Event\Service;

class eventValidate
{
    public function __construct()
    {
        $this->allowedKeys = array(
                "id",
                "buchungskreis",
                "v_schluessel",
                "auftragsnr",
                "bezeichnung",
                "v_land",
                "v_ort",
                "anmeldefrist_beginn",
                "anmeldefrist_ende",
                "v_beginn",
                "v_ende",
                "cpd_konto",
                "erloeskonto",
                "steuerkennzeichen",
                "steuersatz",
                "ansprechpartner",
                "ansprechpartner_tel",
                "organisationseinheit",
                "ansprechpartner_mail",
                "stellvertreter_mail",
                "standardbetrag");
        
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
    
    function buchungskreisValidate($value)
    {
        return $this->defaultValidation("buchungskreis", $value, 4);
    }
    
    function v_schluesselValidate($value)
    {
        return $this->defaultValidation("v_schluessel", $value, 8);
    }
    
    function auftragsnrValidate($value)
    {
        return $this->defaultValidation("auftragsnr", $value, 12);
    }
    
    function bezeichnungValidate($value)
    {
        return $this->defaultValidation("bezeichnung", $value, 50);
    }
    
    function v_landValidate($value)
    {
        return $this->defaultValidation("v_land", $value, 2);
    }
    
    function v_ortValidate($value)
    {
        return $this->defaultValidation("v_ort", $value, 35);
    }
    
    function anmeldefrist_beginnValidate($value)
    {
        return $this->dateValidation("anmeldefrist_beginn", $value);
    }
    
    function anmeldefrist_endeValidate($value)
    {
        return $this->dateValidation("anmeldefrist_ende", $value);
    }
    
    function v_beginnValidate($value)
    {
        return $this->dateValidation("v_beginn", $value);
    }
    
    function v_endeValidate($value)
    {
        return $this->dateValidation("v_ende", $value);
    }
    
    function cpd_kontoValidate($value)
    {
        return $this->defaultValidation("cpd_konto", $value, 10);
    }
    
    function erloeskontoValidate($value)
    {
        return $this->defaultValidation("erloeskonto", $value, 10);
    }
    
    function steuerkennzeichenValidate($value)
    {
        return $this->defaultValidation("steuerkennzeichen", $value, 2);
    }
    
    function steuersatzValidate($value)
    {
        return $this->defaultValidation("steuersatz", $value, 5);
    }
    
    function ansprechpartnerValidate($value)
    {
        return $this->defaultValidation("ansprechpartner", $value, 30);
    }
    
    function ansprechpartner_telValidate($value)
    {
        return $this->defaultValidation("ansprechpartner_tel", $value, 20);
    }
    
    function organisationseinheitValidate($value)
    {
        return $this->defaultValidation("organisationseinheit", $value, 12);
    }
    
    function ansprechpartner_mailValidate($value)
    {
        $bool = $this->requiredValidation("ansprechpartner_mail", $value);
        if($bool){
            return $this->emailValidation("ansprechpartner_mail", $value);
        }
        return false;
    }
    
    function stellvertreter_mailValidate($value)
    {
        if(empty($value)){
            return true;
        }else{
            return $this->emailValidation("stellvertreter_mail", $value);
        }
    }
    
    function standardbetragValidate($value)
    {
        return $this->defaultValidation("standardbetrag", $value, 16);
    }
    
    function defaultValidation($key,$value,$length)
    {
        $bool = $this->requiredValidation($key, $value);
        
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
    
    function dateValidation($key, $value)
    {
        $bool = true;
        if(strlen($value) != 8){
            if($value == ""){
                $this->addError($key, 1);
            }else{
                $this->addError($key, 2, array("maxlength" => 8, "actuallength" => strlen($value)));
            }
            $bool = false;
        }else{
            if(strlen($value) >= 8){
                $year = substr($value, 0, 4);
                if($year < date("Y")){
                    $this->addError($key, 3, array("enteredyear" => $year));
                    $bool = false;
                }

                $month = substr($value, 4, 2); 
                $day   = substr($value, 6, 2);
                if(!checkdate($month, $day, $year)){
                    $this->addError($key, 4, array("entereddate" => $value));
                    $bool = false;
                }
            }
        }
        
        
        if($bool == false){
            return false;
        }
        return true;
    }
    
    function emailValidation($key,$value)
    {
        $bool = true;

        if(filter_var($value, FILTER_VALIDATE_EMAIL) == false){
            $this->addError($key, 5);
            $bool = false;
        }

        if($bool == false){
            return false;
        }
        return true;
    }
    
    public function resetErrorKeyForTesting($key)
    {
        $this->errors[$key] = array();
    }
}