<?php

include_once(dirname(__FILE__).'/../../../../Services/Autoloader/fabAutoloader.php');

/**
 * Test class for eventValidate.
 * Generated by PHPUnit on 2013-01-03 at 11:48:05.
 */
class participantValidateTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var eventValidate
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

    }

}

?>
