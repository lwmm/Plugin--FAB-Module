<?php

namespace Fab\Domain\Participant\Service;

define("REQUIRED", "1");    # array( 1 => array( "error" => 1, "options" => "" ));
define("MAXLENGTH", "2");   # array( 2 => array( "error" => 1, "options" => array( "maxlength" => $maxlength, "actuallength" => $strlen ) ));
define("YEAR", "3");        # array( 3 => array( "error" => 1, "options" => array( "enteredyear" => $year ) ));
define("DATE", "4");        # array( 4 => array( "error" => 1, "options" => array( "entereddate" => $date ) ));  [$date = JJJJMMDD]
define("EMAIL", "5");       # array( 5 => array( "error" => 1, "options" => "" ));
define("DIGITFIELD", "6");  # array( 6 => array( "error" => 1, "options" => "" ));
define("ZIP", "7");         # array( 7 => array( "error" => 1, "options" => "" ));
define("PAYMENT", "8");     # array( 8 => array( "error" => 1, "options" => "" ));
define("BOOL", "9");        # array( 9 => array( "error" => 1, "options" => "" ));

class participantValidate
{
    public function __construct()
    {
        $this->allowedKeys = array(
                "id",
                "event_id",
                "anrede",
                "sprache",
                "titel",
                "nachname",
                "vorname",
                "institut",
                "unternehmen",
                "strasse",
                "plz",
                "ort",
                "land",
                "mail",
                "ust_id_nr",
                "zahlweise",
                "teilnehmer_intern",
                "betrag");
        
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
    
    public function idValidate($value){
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
    
    public function event_idValidate($value){
        if(empty($value)){
            $this->addError("event_id", 1);
            return false;
        }else{
            if(ctype_digit($value)){
                return true;
            }else{
                $this->addError("event_id", 6);
                return false;
            }
        }
    }
    
    public function anredeValidate($value)
    {
        return $this->defaultValidation("anrede",$value, 15);
    }
    
    public function spracheValidate($value)
    {
        return $this->defaultValidation("sprache", $value, 2);
    }
    
    public function titelValidate($value)
    {
        return $this->defaultValidation("titel", $value, 20);
    }
    
    public function nachnameValidate($value)
    {
        return $this->defaultValidation("nachname", $value, 35 , true);
    }
    
    public function vornameValidate($value)
    {
        return $this->defaultValidation("vorname", $value, 35 , true);
    }
    
    public function institutValidate($value)
    {
        return $this->defaultValidation("institut", $value, 35);
    }
    
    public function unternehmenValidate($value)
    {
        return $this->defaultValidation("unternehmen", $value, 35);
    }
    
    public function strasseValidate($value)
    {
        return $this->defaultValidation("strasse", $value, 30);
    }
    
    public function plzValidate($value)
    {
        if($value == ""){
            return true;
        }else{
            $zipcheck = new \Fab\Services\Zipcheck\zipcheck();
            if($this->landValidate($this->array["land"])){
                $ok = $zipcheck->check(strtoupper($this->array["land"]), $value);
                if($ok === 1){
                    return true;
                }else{
                    $this->addError("plz", 7);
                    return false;
                }
            }
        }
    }

    public function ortValidate($value)
    {
        return $this->defaultValidation("ort", $value, 35, true);
    }

    public function landValidate($value)
    {
        return $this->defaultValidation("land", $value, 2);
    }

    public function mailValidate($value)
    {
        $bool = $this->requiredValidation("mail", $value);
        if($bool){
            return $this->emailValidation("mail", $value);
        }
        return false;
    }
    
    public function ust_id_nrValidate($value)
    {
        return $this->defaultValidation("ust_id_nr", $value, 20);
    }
    
    public function zahlweiseValidate($value)
    {
        $bool = $this->defaultValidation("zahlweise", $value, 1, true);

        if(!in_array(strtoupper($value), array("K","U"))){
            $this->addError("zahlweise", 8);
            $bool = false;
        }
        
        if($bool === false){
            return false;
        }else{
            return true;
        }
    }
    
    public function teilnehmer_internValidate($value)
    {
        if(!in_array($value, array("0","1"))){
            $this->addError("teilnehmer_intern", 9);
            return false;
        }else{
            return true;
        }
    }
    
    public function betragValidate($value)
    {
        return $this->defaultValidation("betrag", $value, 16, true);
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
    
    public function emailValidation($key,$value)
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