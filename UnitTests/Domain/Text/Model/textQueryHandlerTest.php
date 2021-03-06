<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include_once(dirname(__FILE__) . '/../../../../Services/Autoloader/fabAutoloader.php');
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_object.class.php';
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_db.class.php';
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_db_mysqli.class.php';
require_once dirname(__FILE__) . '/../../../../../../../c_libraries/lw/lw_registry.class.php';
require_once dirname(__FILE__) . '/../../../Config/phpUnitConfig.php';

/**
 * Test class for eventValidate.
 * Generated by PHPUnit on 2013-01-03 at 11:48:05.
 */
class textQueryHandlerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var eventValidate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $phpUnitConfig = new phpUnitConfig();
        $config = $phpUnitConfig->getConfig();
        
        $db = new lw_db_mysqli($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["db"]);
        $db->connect();
        $this->db = $db;
        
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig(array("plugins" => $config["plugins"],
                                     "plugin_path" => array ("lw" => $config["plugin_path"]["lw"] )));
        
        $this->textQueryHandler = new Fab\Domain\Text\Model\textQueryHandler($this->db);     
        $this->textCommandHandler = new Fab\Domain\Text\Model\textCommandHandler($this->db);
        $this->textCommandHandler->setDebug(false);
        
        $this->assertTrue($this->textCommandHandler->createTable());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->db->setStatement("DROP TABLE t:fab_text ");
        $this->assertTrue($this->db->pdbquery());
    }

    /**
     * @todo Implement test().
     */
    public function testGetAllTextsByCategory()
    {
        $this->fillTable();

        $result = $this->textQueryHandler->getAllTextsByCategory("category1");
        unset($result[0]["id"]);
        unset($result[0]["first_date"]);
        unset($result[0]["last_date"]);
        unset($result[1]["id"]);
        unset($result[1]["first_date"]);
        unset($result[1]["last_date"]);
        
        $assertedArray[] =  array(
                "key"       => "test key", 
                "content"   => "ttttttttttttttttttteeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeessssssssssssssssssssssssssssssssssstttttttttttttttt   content", 
                "language"  => "de", 
                "category"  => "category1");
        $assertedArray[] =  array(
                "key"       => "test key", 
                "content"   => "ttttttttttttttttttteeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeessssssssssssssssssssssssssssssssssstttttttttttttttt   content", 
                "language"  => "en", 
                "category"  => "category1");
        
        $this->assertEquals($assertedArray,$result);
        
        try{
            $this->textQueryHandler->getAllTextsByCategory("nichtVorhanden");
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
    }
    
    public function testGetAllUniqueCategories()
    {
        $this->fillTable();
        
        $result = $this->textQueryHandler->getAllUniqueCategories();
        foreach($result as $value){
            $categories[] = $value["category"];
        }
        $this->assertEquals(array("category1","category2","category4"),$categories);
    }
    
    public function testGetAllUniqueLanguages()
    {
        $this->fillTable();
        
        $result = $this->textQueryHandler->getAllUniqueLanguages();
        foreach($result as $value){
            $languages[] = $value["language"];
        }
        $this->assertEquals(array("de","en"),$languages);
    }
    
    public function testLanguageExists()
    {
        $this->fillTable();
        
        $this->assertFalse($this->textQueryHandler->languageExists("fr"));
        $this->assertTrue($this->textQueryHandler->languageExists("de"));
    }
    
    public function testCategoryExists()
    {
        $this->fillTable();
        
        $this->assertFalse($this->textQueryHandler->categoryExists("abcdefh"));
        $this->assertTrue($this->textQueryHandler->categoryExists("category4"));
    }
    
    public function testGetTextById()
    {
        $this->fillTable();
        
        $result = $this->textQueryHandler->getTextById(1);
        unset($result["id"]);
        unset($result["first_date"]);
        unset($result["last_date"]);
        
        $array = array(
                "key"       => "test key", 
                "content"   => "ttttttttttttttttttteeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeessssssssssssssssssssssssssssssssssstttttttttttttttt   content", 
                "language"  => "de", 
                "category"  => "category1");
        
        $this->assertEquals($array, $result);
        
        $this->assertEmpty($this->textQueryHandler->getTextById(10));
    }

    public function fillTable()
    {
        $array = array (
        
        array(
                "key"       => "test key", 
                "content"   => "ttttttttttttttttttteeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeessssssssssssssssssssssssssssssssssstttttttttttttttt   content", 
                "language"  => "de", 
                "category"  => "category1"),
        
         array(
                "key"       => "test key", 
                "content"   => "ttttttttttttttttttteeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeessssssssssssssssssssssssssssssssssstttttttttttttttt   content", 
                "language"  => "de", 
                "category"  => "category2"),
        
        array(
                "key"       => "test key", 
                "content"   => "ttttttttttttttttttteeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeessssssssssssssssssssssssssssssssssstttttttttttttttt   content", 
                "language"  => "en", 
                "category"  => "category1"),
        
        array(
                "key"       => "test key", 
                "content"   => "ttttttttttttttttttteeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeessssssssssssssssssssssssssssssssssstttttttttttttttt   content", 
                "language"  => "de", 
                "category"  => "category4"));
        
        foreach($array as $entry){
            $this->db->setStatement("INSERT INTO t:fab_text ( `key`, content, language, category, first_date, last_date ) VALUES ( :key, :content, :language, :category, :first_date, :last_date ) ");
            $this->db->bindParameter("key", "s", $entry['key']);
            $this->db->bindParameter("content", "s", $entry['content']);
            if($entry['language'] == ""){
                $this->db->bindParameter("language", "s", "de");
            }else{
                $this->db->bindParameter("language", "s", $entry['language']);
            }
            $this->db->bindParameter("category", "s", $entry['category']);
            $this->db->bindParameter("first_date", "i", date("YmdHis"));
            $this->db->bindParameter("last_date", "i", date("YmdHis"));
            $this->db->pdbquery();
        }
    }
}