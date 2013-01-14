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
class participantQueryHandlerTest extends \PHPUnit_Framework_TestCase {

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
        $this->participantQueryHandler = new Fab\Domain\Participant\Model\participantQueryHandler($this->db);     
        $this->participantCommandHandler = new Fab\Domain\Participant\Model\participantCommandHandler($this->db);
        $this->participantCommandHandler->setDebug(false);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        #$this->db->setStatement("DROP TABLE t:fab_tagungen ");
        #$this->db->pdbquery();
    }

    /**
     * @todo Implement test().
     */
    public function testLoadParticipantsByEvent()
    {
        $this->createTable();
        
        $array = array(
                "anrede"            => "Herr",
                "sprache"           => "de",
                "titel"             => "Prof.",
                "nachname"          => "Meyer",
                "vorname"           => "Karl",
                "institut"          => "GB-F",
                "unternehmen"       => "FZJ",
                "strasse"           => "Wilhelm_Johnen-Str.",
                "plz"               => "52428",
                "ort"               => "Jülich",
                "land"              => "de",
                "mail"              => "m.mustermann@fzj-juelich.de",
                "ust_id_nr"         => "986743-36436-34g",
                "zahlweise"         => "K",
                "teilnehmer_intern" => "1",
                "betrag"            => "105,73"
            );
        $this->fillTable(1, $array);
        $this->fillTable(3, $array);
        $this->fillTable(2, $array);
        $this->fillTable(1, $array);

        $result = $this->participantQueryHandler->loadParticipantsByEvent(1);
        unset($result[0]["id"]);
        unset($result[0]["first_date"]);
        unset($result[0]["last_date"]);
        unset($result[1]["id"]);
        unset($result[1]["first_date"]);
        unset($result[1]["last_date"]);
        
        $assertedArray = array(
            0 => array(
                "event_id"          => 1,
                "anrede"            => "Herr",
                "sprache"           => "de",
                "titel"             => "Prof.",
                "nachname"          => "Meyer",
                "vorname"           => "Karl",
                "institut"          => "GB-F",
                "unternehmen"       => "FZJ",
                "strasse"           => "Wilhelm_Johnen-Str.",
                "plz"               => "52428",
                "ort"               => "Jülich",
                "land"              => "de",
                "mail"              => "m.mustermann@fzj-juelich.de",
                "ust_id_nr"         => "986743-36436-34g",
                "zahlweise"         => "K",
                "teilnehmer_intern" => "1",
                "betrag"            => "105,73"),
            1 => array(
                "event_id"          => 1,
                "anrede"            => "Herr",
                "sprache"           => "de",
                "titel"             => "Prof.",
                "nachname"          => "Meyer",
                "vorname"           => "Karl",
                "institut"          => "GB-F",
                "unternehmen"       => "FZJ",
                "strasse"           => "Wilhelm_Johnen-Str.",
                "plz"               => "52428",
                "ort"               => "Jülich",
                "land"              => "de",
                "mail"              => "m.mustermann@fzj-juelich.de",
                "ust_id_nr"         => "986743-36436-34g",
                "zahlweise"         => "K",
                "teilnehmer_intern" => "1",
                "betrag"            => "105,73")
        );
        
        $this->assertEquals($assertedArray, $result);
    }
    
    public function testLoadParticipantById()
    {
        $result = $this->participantQueryHandler->loadParticipantById(1);
        unset($result["id"]);
        unset($result["first_date"]);
        unset($result["last_date"]);
        
        $assertedArray = array(
                "event_id"          => 1,
                "anrede"            => "Herr",
                "sprache"           => "de",
                "titel"             => "Prof.",
                "nachname"          => "Meyer",
                "vorname"           => "Karl",
                "institut"          => "GB-F",
                "unternehmen"       => "FZJ",
                "strasse"           => "Wilhelm_Johnen-Str.",
                "plz"               => "52428",
                "ort"               => "Jülich",
                "land"              => "de",
                "mail"              => "m.mustermann@fzj-juelich.de",
                "ust_id_nr"         => "986743-36436-34g",
                "zahlweise"         => "K",
                "teilnehmer_intern" => "1",
                "betrag"            => "105,73");
        
        $this->assertEquals($assertedArray, $result);
    }
    
    public function testDropTable()
    {
        $this->db->setStatement("DROP TABLE t:fab_teilnehmer ");
        $this->db->pdbquery();
    }
    
    public function fillTable($event_id, $array)
    {
        $this->db->setStatement("INSERT INTO t:fab_teilnehmer ( event_id, anrede, sprache, titel, nachname, vorname, institut, unternehmen, strasse, plz, ort, land, mail, ust_id_nr, zahlweise, teilnehmer_intern, betrag, first_date, last_date ) VALUES ( :event_id, :anrede, :sprache, :titel, :nachname, :vorname, :institut, :unternehmen, :strasse, :plz, :ort, :land, :mail, :ust_id_nr, :zahlweise, :teilnehmer_intern, :betrag, :first_date, :last_date ) ");
        $this->db->bindParameter("event_id", "i", $event_id);
        $this->db->bindParameter("anrede", "s", $array['anrede']);
        $this->db->bindParameter("sprache", "s", $array['sprache']);
        $this->db->bindParameter("titel", "s", $array['titel']);
        $this->db->bindParameter("nachname", "s", $array['nachname']);
        $this->db->bindParameter("vorname", "s", $array['vorname']);
        $this->db->bindParameter("institut", "s", $array['institut']);
        $this->db->bindParameter("unternehmen", "s", $array['unternehmen']);
        $this->db->bindParameter("strasse", "s", $array['strasse']);
        $this->db->bindParameter("plz", "s", $array['plz']);
        $this->db->bindParameter("ort", "s", $array['ort']);
        $this->db->bindParameter("land", "s", $array['land']);
        $this->db->bindParameter("mail", "s", $array['mail']);
        $this->db->bindParameter("ust_id_nr", "s", $array['ust_id_nr']);
        $this->db->bindParameter("zahlweise", "s", $array['zahlweise']);
        $this->db->bindParameter("teilnehmer_intern", "i", $array['teilnehmer_intern']);
        $this->db->bindParameter("betrag", "s", $array['betrag']);
        $this->db->bindParameter("first_date", "i", date("YmdHis"));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));
        
        $this->db->pdbquery();
    }        
    
    public function createTable()
    {
        $this->assertTrue($this->participantCommandHandler->createTable());
        $this->assertTrue($this->db->tableExists($this->db->gt("fab_teilnehmer")));
    }
}