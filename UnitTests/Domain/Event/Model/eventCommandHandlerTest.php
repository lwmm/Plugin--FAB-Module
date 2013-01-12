<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include_once(dirname(__FILE__) . '/../../../../Services/Autoloader/fabAutoloader.php');
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_object.class.php';
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_db.class.php';
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_db_mysqli.class.php';
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_registry.class.php';

/**
 * Test class for eventValidate.
 * Generated by PHPUnit on 2013-01-03 at 11:48:05.
 */
class eventCommandHandlerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var eventValidate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $db = new lw_db_mysqli("root", "", "localhost", "fab_test");
        $db->connect();
        $this->db = $db;
        
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig(array("plugins" => "C:/xampp/htdocs/c38/contentory/c_server/plugins/",
                                     "plugin_path" => array ("lw" => "C:/xampp/htdocs/c38/contentory/c_server/modules/lw/")));
        $this->eventCommandHandler = new Fab\Domain\Event\Model\eventCommandHandler($this->db);
        $this->eventCommandHandler->setDebug(false);        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @todo Implement test().
     */
    public function testTableCreation()
    {
        $this->assertTrue($this->eventCommandHandler->createTable());
        $this->assertTrue($this->db->tableExists($this->db->gt("fab_tagungen")));
    }
    
    public function testAddEvent()
    {
        $array = array(
            "buchungskreis" => "15",
            "v_schluessel" => "51543",
            "auftragsnr" => "45135060",
            "bezeichnung" => "Tagung 1",
            "v_land" => "de",
            "v_ort" => "Ehrenfeld",
            "anmeldefrist_beginn" => "20130701",
            "anmeldefrist_ende" => "20130704",
            "v_beginn" => "20130905",
            "v_ende" => "20130916",
            "cpd_konto" => "200270",
            "erloeskonto" => "4510",
            "steuerkennzeichen" => "98",
            "steuersatz" => "9",
            "ansprechpartner" => "Max Mustermann",
            "ansprechpartner_tel" => "1111",
            "organisationseinheit" => "GB-F",
            "ansprechpartner_mail" => "m.mustermann@fz-juelich.de",
            "stellvertreter_mail" => "",
            "standardbetrag" => "100",
        );
               
        $eventValueObjectMock = $this->getEventValueObjectMock($array);
        $eventValueObjectMock->expects($this->at(0))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["buchungskreis"]));
        $eventValueObjectMock->expects($this->at(1))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["v_schluessel"]));
        $eventValueObjectMock->expects($this->at(2))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["auftragsnr"]));
        $eventValueObjectMock->expects($this->at(3))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["bezeichnung"]));
        $eventValueObjectMock->expects($this->at(4))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["v_land"]));
        $eventValueObjectMock->expects($this->at(5))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["v_ort"]));
        $eventValueObjectMock->expects($this->at(6))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["anmeldefrist_beginn"]));
        $eventValueObjectMock->expects($this->at(7))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["anmeldefrist_ende"]));
        $eventValueObjectMock->expects($this->at(8))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["v_beginn"]));
        $eventValueObjectMock->expects($this->at(9))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["v_ende"]));
        $eventValueObjectMock->expects($this->at(10))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["cpd_konto"]));
        $eventValueObjectMock->expects($this->at(11))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["erloeskonto"]));
        $eventValueObjectMock->expects($this->at(12))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["steuerkennzeichen"]));
        $eventValueObjectMock->expects($this->at(13))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["steuersatz"]));
        $eventValueObjectMock->expects($this->at(14))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["ansprechpartner"]));
        $eventValueObjectMock->expects($this->at(15))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["ansprechpartner_tel"]));
        $eventValueObjectMock->expects($this->at(16))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["organisationseinheit"]));
        $eventValueObjectMock->expects($this->at(17))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["ansprechpartner_mail"]));
        $eventValueObjectMock->expects($this->at(18))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["stellvertreter_mail"]));
        $eventValueObjectMock->expects($this->at(19))
                             ->method("getValueByKey")
                             ->will($this->returnValue($array["standardbetrag"]));
        
        $this->assertTrue($this->eventCommandHandler->addEvent($eventValueObjectMock));
        
        $this->db->setStatement("SELECT * FROM t:fab_tagungen WHERE    id = 1 ");
        $result = $this->db->pselect1();
        unset($result["id"]);
        unset($result["first_date"]);
        unset($result["last_date"]);
        $this->assertEquals($result,$array);
    }
    
    public function testSaveEvent() 
    {
            $array = array(
                "buchungskreis" => "16",
                "v_schluessel" => "51543",
                "auftragsnr" => "45135060",
                "bezeichnung" => "Tagung 2",
                "v_land" => "de",
                "v_ort" => "Ehrenfeld",
                "anmeldefrist_beginn" => "20130701",
                "anmeldefrist_ende" => "20130704",
                "v_beginn" => "20130905",
                "v_ende" => "20130916",
                "cpd_konto" => "200270",
                "erloeskonto" => "4510",
                "steuerkennzeichen" => "98",
                "steuersatz" => "9",
                "ansprechpartner" => "Max Mustermann",
                "ansprechpartner_tel" => "1111",
                "organisationseinheit" => "GB-F",
                "ansprechpartner_mail" => "m.mustermann@fz-juelich.de",
                "stellvertreter_mail" => "",
                "standardbetrag" => "100",
            );
            
            $eventValueObjectMock = $this->getEventValueObjectMock($array);
            $eventValueObjectMock->expects($this->at(0))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["buchungskreis"]));
            $eventValueObjectMock->expects($this->at(1))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["v_schluessel"]));
            $eventValueObjectMock->expects($this->at(2))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["auftragsnr"]));
            $eventValueObjectMock->expects($this->at(3))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["bezeichnung"]));
            $eventValueObjectMock->expects($this->at(4))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["v_land"]));
            $eventValueObjectMock->expects($this->at(5))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["v_ort"]));
            $eventValueObjectMock->expects($this->at(6))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["anmeldefrist_beginn"]));
            $eventValueObjectMock->expects($this->at(7))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["anmeldefrist_ende"]));
            $eventValueObjectMock->expects($this->at(8))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["v_beginn"]));
            $eventValueObjectMock->expects($this->at(9))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["v_ende"]));
            $eventValueObjectMock->expects($this->at(10))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["cpd_konto"]));
            $eventValueObjectMock->expects($this->at(11))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["erloeskonto"]));
            $eventValueObjectMock->expects($this->at(12))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["steuerkennzeichen"]));
            $eventValueObjectMock->expects($this->at(13))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["steuersatz"]));
            $eventValueObjectMock->expects($this->at(14))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["ansprechpartner"]));
            $eventValueObjectMock->expects($this->at(15))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["ansprechpartner_tel"]));
            $eventValueObjectMock->expects($this->at(16))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["organisationseinheit"]));
            $eventValueObjectMock->expects($this->at(17))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["ansprechpartner_mail"]));
            $eventValueObjectMock->expects($this->at(18))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["stellvertreter_mail"]));
            $eventValueObjectMock->expects($this->at(19))
                                 ->method("getValueByKey")
                                 ->will($this->returnValue($array["standardbetrag"]));
            
            $this->assertTrue($this->eventCommandHandler->saveEvent(1, $eventValueObjectMock));
            
            $this->db->setStatement("SELECT * FROM t:fab_tagungen WHERE id = 1 ");
            $entry = $this->db->pselect1();
            
            $this->assertEquals(16, intval($entry["buchungskreis"]));
            $this->assertEquals("Tagung 2", $entry["bezeichnung"]);
            $this->assertFalse("Tagung 1" == $entry["bezeichnung"]);
    }
    
    public function testSaveReplacement()
    {
        $this->assertTrue($this->eventCommandHandler->saveReplacement(1, "auto@logic-works.de"));

        $this->db->setStatement("SELECT * FROM t:fab_tagungen WHERE id = 1 ");
        $entry = $this->db->pselect1();

        $this->assertEquals("auto@logic-works.de", $entry["stellvertreter_mail"]);
        $this->assertFalse("" == $entry["stellvertreter_mail"]);
    }
    
    public function testDeleteEvent()
    {          
        $eventEntityMock = $this->getEventEntityMock();
        $eventEntityMock->expects($this->once())
                        ->method("isDeleteable")
                        ->will($this->returnValue(true));
        $eventEntityMock->expects($this->exactly(2))
                        ->method("getId")
                        ->will($this->returnValue(1));

        $this->assertTrue($this->eventCommandHandler->deleteEvent($eventEntityMock));
    }
    
    public function testDropTable()
    {
        $this->db->setStatement("DROP TABLE t:fab_tagungen ");
        $this->db->pdbquery();
    }
    
    public function getEventEntityMock()
    {
        /* $this->getMock(
         *      Name der zu mockenden Klasse,
         *      array( Functionsnamen ),            [ leeres Array => alle Functionen werden gemockt]
         *      array( uebergebene Konstuktor Argumente ),
         *      "",                                 [ Klassenname des Mockobjektes ]
         *      bool                                [ Den Konstruktor der Original Klasse aufrufen ]
         *  );
         */
        return $this->getMock("\\Fab\\Domain\\Event\\Object\\event", array(), array(), "", false);
    }
    
    public function getEventValueObjectMock($array)
    {
        return $this->getMock("\\LWddd\\ValueObject", array(), array($array), "", true);
    }
}
?>