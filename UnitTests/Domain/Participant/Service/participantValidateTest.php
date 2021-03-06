<?php

include_once(dirname(__FILE__).'/../../../../Services/Autoloader/fabAutoloader.php');

/**
 * Test class for participantValidate.
 * Generated by PHPUnit on 2013-01-03 at 11:48:05.
 */
class participantValidateTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var participantValidate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig(array("plugins" => "C:/xampp/htdocs/c38/contentory/c_server/plugins/"));
        $this->participantValidate = new Fab\Domain\Participant\Service\participantValidate();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @todo Implement testValidate().
     */
    public function testValidate()
    {
        $array = array(
                "id"                    => "",
                "event_id"              => "1",
                "anrede"                => "Herr",
                "sprache"               => "de",
                "titel"                 => "Prof.",
                "nachname"              => "Meyer",
                "vorname"               => "Karl",
                "institut"              => "GB-F",
                "unternehmen"           => "FZentrumJuelich",
                "unternehmenshortcut"   => "FZJ",
                "strasse"               => "Wilhelm_Johnen-Str.",
                "plz"                   => "52428",
                "ort"                   => "Jülich",
                "land"                  => "de",
                "mail"                  => "m.mustermann@fzj-juelich.de",
                "ust_id_nr"             => "986743-36436-34g",
                "zahlweise"             => "K",
                "teilnehmer_intern"     => "1",
                "betrag"                => "105,73"
            );
        
        $this->participantValidate->setValues($array);
        $this->assertTrue($this->participantValidate->validate());
        $this->assertEquals(array(), $this->participantValidate->getErrors());
        
        $array2 = array(
                "id"                    => "",
                "event_id"              => "", #required
                "anrede"                => "",
                "sprache"               => "",
                "titel"                 => "",
                "nachname"              => "", #required
                "vorname"               => "", #required
                "institut"              => "",
                "unternehmen"           => "",
                "unternehmenshortcut"   => "",
                "strasse"               => "",
                "plz"                   => "",
                "ort"                   => "", #required
                "land"                  => "",
                "mail"                  => "", #required
                "ust_id_nr"             => "",
                "zahlweise"             => "", #required
                "teilnehmer_intern"     => "", #bool
                "betrag"                => ""  #required
            );
        
        $this->participantValidate->setValues($array2);
        $this->participantValidate->validate();
        $error = $this->participantValidate->getErrors();
        $this->assertTrue(is_array($error));
        
        $this->assertFalse(array_key_exists("id", $error));
        $this->assertEquals($error["event_id"], array(1 => array("error" => 1, "options" => "")));
        $this->assertFalse(array_key_exists("anrede", $error));
        $this->assertFalse(array_key_exists("sprache", $error));
        $this->assertFalse(array_key_exists("titel", $error));
        $this->assertEquals($error["nachname"], array(1 => array("error" => 1, "options" => "")));
        $this->assertEquals($error["vorname"], array(1 => array("error" => 1, "options" => "")));
        $this->assertFalse(array_key_exists("institut", $error));
        $this->assertFalse(array_key_exists("unternehmen", $error));
        $this->assertFalse(array_key_exists("unternehmenshortcut", $error));
        $this->assertFalse(array_key_exists("strasse", $error));
        $this->assertFalse(array_key_exists("plz", $error));
        $this->assertEquals($error["ort"], array(1 => array("error" => 1, "options" => "")));
        $this->assertFalse(array_key_exists("land", $error));
        $this->assertEquals($error["mail"], array(1 => array("error" => 1, "options" => "")));
        $this->assertFalse(array_key_exists("ust_id_nr", $error));
        $this->assertEquals($error["zahlweise"], array(1 => array("error" => 1, "options" => ""),8 => array("error" => 1, "options" => "")));
        $this->assertEquals($error["teilnehmer_intern"], array(9 => array("error" => 1, "options" => "")));
        $this->assertEquals($error["betrag"], array(1 => array("error" => 1, "options" => "")));
    }
    
    public function testidValidate()
    {
        $this->assertTrue($this->participantValidate->idValidate(""));
        $this->assertFalse(array_key_exists("id", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->idValidate("1"));
        $this->assertFalse(array_key_exists("id", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->idValidate("a"));
        $assertError = array(6 => array("error" => 1, "options" => ""));
        $this->assertEquals($assertError, $this->participantValidate->getErrorsByKey("id"));
    }
    
    public function testevent_IdValidate()
    {
        $this->assertTrue($this->participantValidate->event_idValidate("1"));
        $this->assertFalse(array_key_exists("event_id", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->event_idValidate(""));
        $this->error1RequiredTest("event_id");
        
        
        $this->assertFalse($this->participantValidate->event_idValidate("a"));
        $assertError = array(6 => array("error" => 1, "options" => ""));
        $this->assertEquals($assertError, $this->participantValidate->getErrorsByKey("event_id"));
    }
    
    public function testanredeValidate()
    {
        $this->assertTrue($this->participantValidate->anredeValidate(""));
        $this->assertFalse(array_key_exists("anrede", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->anredeValidate("Herr"));
        $this->assertFalse(array_key_exists("anrede", $this->participantValidate->getErrors()));

        $this->assertFalse($this->participantValidate->anredeValidate("Herrrrrrrrrrrrrrrrrr"));
        $this->error2LengthTest("anrede", 15, "Herrrrrrrrrrrrrrrrrr");
    }
    
    public function testspracheValidate()
    {
        $this->assertTrue($this->participantValidate->spracheValidate(""));
        $this->assertFalse(array_key_exists("sprache", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->spracheValidate("de"));
        $this->assertFalse(array_key_exists("sprache", $this->participantValidate->getErrors()));

        $this->assertFalse($this->participantValidate->spracheValidate("dee"));
        $this->error2LengthTest("sprache", 2, "dee");
    }
    
    public function testtitelValidate()
    {
        $this->assertTrue($this->participantValidate->titelValidate(""));
        $this->assertFalse(array_key_exists("titel", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->titelValidate("Prof."));
        $this->assertFalse(array_key_exists("titel", $this->participantValidate->getErrors()));

        $this->assertFalse($this->participantValidate->titelValidate("Profffffffffffffffff."));
        $this->error2LengthTest("titel", 20, "Profffffffffffffffff.");
    }
    
    public function testnachnameValidate()
    {
        $this->assertTrue($this->participantValidate->nachnameValidate("Mustermann"));
        $this->assertFalse(array_key_exists("nachname", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->nachnameValidate(""));
        $this->error1RequiredTest("nachname");
        
        $this->assertFalse($this->participantValidate->nachnameValidate("MustermannMustermannMustermannMustermann"));
        $this->error2LengthTest("nachname", 35, "MustermannMustermannMustermannMustermann");
    }
    
    public function testvornameValidate()
    {
        $this->assertTrue($this->participantValidate->vornameValidate("Maximilian"));
        $this->assertFalse(array_key_exists("vorname", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->vornameValidate(""));
        $this->error1RequiredTest("vorname");
        
        $this->assertFalse($this->participantValidate->vornameValidate("MaximilianMaximilianMaximilianMaximilian"));
        $this->error2LengthTest("vorname", 35, "MaximilianMaximilianMaximilianMaximilian");
    }
    
    public function testinstitutValidate()
    {
        $this->assertTrue($this->participantValidate->institutValidate("Forschungszentrum"));
        $this->assertFalse(array_key_exists("institut", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->institutValidate(""));
        $this->assertFalse(array_key_exists("institut", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->institutValidate("ForschungszentrumForschungszentrumForschungszentrum"));
        $this->error2LengthTest("institut", 35, "ForschungszentrumForschungszentrumForschungszentrum");
    }
    
    public function testunternehmenValidate()
    {
        $this->assertTrue($this->participantValidate->unternehmenValidate("Forschungszentrum"));
        $this->assertFalse(array_key_exists("unternehmen", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->unternehmenValidate(""));
        $this->assertFalse(array_key_exists("unternehmen", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->unternehmenValidate("ForschungszentrumForschungszentrumForschungszentrum"));
        $this->error2LengthTest("unternehmen", 35, "ForschungszentrumForschungszentrumForschungszentrum");
    }
    
    public function testunternehmenshortcutValidate()
    {
        $this->assertTrue($this->participantValidate->unternehmenshortcutValidate("FZJ"));
        $this->assertFalse(array_key_exists("unternehmenshortcut", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->unternehmenshortcutValidate(""));
        $this->assertFalse(array_key_exists("unternehmenshortcut", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->unternehmenshortcutValidate("Forschungszentrum"));
        $this->error2LengthTest("unternehmenshortcut", 10, "Forschungszentrum");
    }
    
    public function teststrasseValidate()
    {
        $this->assertTrue($this->participantValidate->strasseValidate("Rheinstr."));
        $this->assertFalse(array_key_exists("strasse", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->strasseValidate(""));
        $this->assertFalse(array_key_exists("strasse", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->strasseValidate("Rheinstr.Rheinstr.Rheinstr.Rheinstr."));
        $this->error2LengthTest("strasse", 30, "Rheinstr.Rheinstr.Rheinstr.Rheinstr.");
    }
    
    public function testplzValidate()
    {
        $this->assertTrue($this->participantValidate->plzValidate(""));
        $this->assertFalse(array_key_exists("strasse", $this->participantValidate->getErrors()));
        
        $this->participantValidate->setValues(array("land" => "de"));
        $this->assertTrue($this->participantValidate->plzValidate("50823"));
        $this->assertFalse(array_key_exists("strasse", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->plzValidate("508233333"));
        $assertedError = array(7 => array("error" => 1 , "options" => ""));
        $this->assertEquals($assertedError, $this->participantValidate->getErrorsByKey("plz"));
    }
    
    public function testortValidate()
    {
        $this->assertTrue($this->participantValidate->ortValidate("Ranzel"));
        $this->assertFalse(array_key_exists("ort", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->ortValidate(""));
        $this->error1RequiredTest("ort");
        
        $this->assertFalse($this->participantValidate->ortValidate("RanzelRanzelRanzelRanzelRanzelRanzelRanzel"));
        $this->error2LengthTest("ort", 35, "RanzelRanzelRanzelRanzelRanzelRanzelRanzel");
    }
    
    public function testlandValidate()
    {
        $this->assertTrue($this->participantValidate->landValidate("de"));
        $this->assertFalse(array_key_exists("land", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->landValidate(""));
        $this->assertFalse(array_key_exists("land", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->landValidate("deee"));
        $this->error2LengthTest("land", 2, "deee");
    }
    
    public function testmailValidate()
    {
        $this->assertTrue($this->participantValidate->mailValidate("m.mustermann@fzj-juelich.de"));
        $this->assertFalse(array_key_exists("mail", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->mailValidate(""));
        $assertError = array(1 => array("error" => 1, "options" => ""));
        $this->assertEquals($assertError, $this->participantValidate->getErrorsByKey("mail"));
        $this->participantValidate->resetErrorKeyForTesting("mail");
        
        $this->assertFalse($this->participantValidate->mailValidate("hallo@hallo"));
        $assertError = array(5 => array("error" => 1, "options" => ""));
        $this->assertEquals($assertError, $this->participantValidate->getErrorsByKey("mail"));
    }
    
    public function testust_id_nrValidate()
    {
        $this->assertTrue($this->participantValidate->ust_id_nrValidate("986743-36436-34g"));
        $this->assertFalse(array_key_exists("ust_id_nr", $this->participantValidate->getErrors()));
        
        $this->assertTrue($this->participantValidate->ust_id_nrValidate(""));
        $this->assertFalse(array_key_exists("ust_id_nr", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->ust_id_nrValidate("986743-36436-34g1203541"));
        $this->error2LengthTest("ust_id_nr", 20, "986743-36436-34g1203541");
    }
    
    public function testzahlweiseValidate()
    {
        $this->assertTrue($this->participantValidate->zahlweiseValidate("U"));
        $this->assertFalse(array_key_exists("zahlweise", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->zahlweiseValidate(""));
        $assertedErrors = array(
            1 => array("error" => 1, "options" => ""),
            8 => array("error" => 1, "options" => "")
            );
        $this->assertEquals($assertedErrors,$this->participantValidate->getErrorsByKey("zahlweise"));
        $this->participantValidate->resetErrorKeyForTesting("zahlweise");
        
        $this->assertFalse($this->participantValidate->zahlweiseValidate("UU"));
        $assertedErrors = array(
            2 => array("error" => 1, "options" => array("maxlength" => 1, "actuallength" => strlen("UU"))),
            8 => array("error" => 1, "options" => "")
            );
        $this->assertEquals($assertedErrors,$this->participantValidate->getErrorsByKey("zahlweise"));
        $this->participantValidate->resetErrorKeyForTesting("zahlweise");
        
        $this->assertFalse($this->participantValidate->zahlweiseValidate("B"));
        $assertedErrors = array(8 => array("error" => 1, "options" => ""));
        $this->assertEquals($assertedErrors,$this->participantValidate->getErrorsByKey("zahlweise"));
    }
    
    public function testteilnehmer_internValidate()
    {
        $this->assertTrue($this->participantValidate->teilnehmer_internValidate("1"));
        $this->assertFalse(array_key_exists("teilnehmer_intern", $this->participantValidate->getErrors()));
        $this->assertTrue($this->participantValidate->teilnehmer_internValidate("0"));
        $this->assertFalse(array_key_exists("teilnehmer_intern", $this->participantValidate->getErrors()));  
        
        $this->assertFalse($this->participantValidate->teilnehmer_internValidate("9"));
        $assertedErrors = array(9 => array("error" => 1, "options" => ""));
        $this->assertEquals($assertedErrors,$this->participantValidate->getErrorsByKey("teilnehmer_intern"));
        $this->participantValidate->resetErrorKeyForTesting("teilnehmer_intern");
        
        $this->assertFalse($this->participantValidate->teilnehmer_internValidate("a"));
        $assertedErrors = array(9 => array("error" => 1, "options" => ""));
        $this->assertEquals($assertedErrors,$this->participantValidate->getErrorsByKey("teilnehmer_intern"));
        $this->participantValidate->resetErrorKeyForTesting("teilnehmer_intern");
        
        $this->assertFalse($this->participantValidate->teilnehmer_internValidate(""));
        $assertedErrors = array(9 => array("error" => 1, "options" => ""));
        $this->assertEquals($assertedErrors,$this->participantValidate->getErrorsByKey("teilnehmer_intern"));
    }
    
    public function testbetragValidate()
    {
        $this->assertTrue($this->participantValidate->betragValidate("100,13"));
        $this->assertFalse(array_key_exists("betrag", $this->participantValidate->getErrors()));
        
        $this->assertFalse($this->participantValidate->betragValidate(""));
        $this->error1RequiredTest("betrag");
        
        $this->assertFalse($this->participantValidate->betragValidate("12345678901234,56"));
        $this->error2LengthTest("betrag", 16, "12345678901234,56");
    }


    public function error1RequiredTest($key)
    {        
        $assertedError = array( 1 => array("error" => 1, "options" => ""));
        $this->assertEquals($assertedError, $this->participantValidate->getErrorsByKey($key));
        $this->participantValidate->resetErrorKeyForTesting($key);
    }
    
    public function error2LengthTest($key, $length, $value)
    {
        $assertedError = array( 2 => array("error" => 1, "options" => array("maxlength" => $length, "actuallength" => strlen($value))));
        $this->assertEquals($assertedError, $this->participantValidate->getErrorsByKey($key));
        $this->participantValidate->resetErrorKeyForTesting($key);
    }
}

?>
