<?php
namespace Fab\Service\TableInstaller;
use \Fab\Domain\Country\Model\countryCommandHandler as countryCommandHandler;
use \Fab\Domain\Event\Model\eventCommandHandler as eventCommandHandler;
use \Fab\Domain\Participant\Model\participantCommandHandler as participantCommandHandler;
use \Fab\Domain\Text\Model\textCommandHandler as textCommandHandler;

class tableInstaller
{
    public function __construct($db) 
    {
        $this->db = $db;
    }
    
    public function execute()
    {
        $countryCommandHandler = new countryCommandHandler($this->db);
        $eventCommandHandler = new eventCommandHandler($this->db);
        $participantCommandHandler = new participantCommandHandler($this->db);
        $textCommandHandler = new textCommandHandler($this->db);
        
        $ok = $countryCommandHandler->createTable();
        if(!$ok){
            throw new Exception('...');
        }
        
        $ok = $countryCommandHandler->importCountries();
        if(!$ok){
            throw new Exception('...');
        }
        
        $ok = $eventCommandHandler->createTable();
        if(!$ok){
            throw new Exception('...');
        }
        
        $ok = $participantCommandHandler->createTable();
        if(!$ok){
            throw new Exception('...');
        }
        
        $ok = $textCommandHandler->createTable();
        if(!$ok){
            throw new Exception('...');
        }
        
        return true;
    }
}