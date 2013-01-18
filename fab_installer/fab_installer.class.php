<?php

class fab_installer extends lw_plugin
{
    public function __construct()
    {
        lw_plugin::__construct();
        
        
    }
    
    public function buildPageOutput()
    {
        include_once(dirname(__FILE__).'/../Services/Autoloader/fabAutoloader.php');
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig($this->config);

        $this->tableInstaller = new \Fab\Services\TableInstaller\tableInstaller($this->db);
        $this->writeDemoData = new \Fab\fab_installer\Service\fab_installer_demoData_write($this->db);
        $this->mainTables = new \Fab\fab_installer\Service\fab_installer_handlerMainTables($this->db);
        
        return $this->outputFabInstaller();
    }
    
    public function outputFabInstaller()
    {
        if($this->params['showui'] & $this->params['showui'] == 1){
            switch ($this->request->getInt("install")) {
                case 1:
                    $this->tableInstaller->execute();
                    $this->pageReload($this->config["url"]["client"]."index.php?index=".$this->request->getInt("index"));
                    break;

                case 2:
                    #$this->tableInstaller->execute();
                    $this->writeDemoData->installDemoEvent();
                    $this->writeDemoData->installDemoParticipants();
                    $this->pageReload($this->config["url"]["client"]."index.php?index=".$this->request->getInt("index"));
                    break;
                    
                default:
                    return $this->buildTemplate($this->mainTables->checkTableExistance());
                    break;
            }
        }else{
            $installer = new tableInstaller($this->db);
            $installer->execute();
            if($installer) echo "ERLEDIGT";
        }
    }

    function buildLink($nr=false)
    {
        $array['index'] = $this->request->getInt("index");
        if($nr) $array['install'] = $nr;
        $url = $this->config["url"]["client"]."index.php?".http_build_query($array);
        $url = str_replace("&amp;", "&", $url);
        return $url;
    }
    
    public function buildTemplate($array)
    {
        $template = file_get_contents(dirname(__FILE__) . '/templates/tableinstaller.tpl.html');
        $tpl = new lw_te($template);
        $tpl->reg("icon_done", $this->config["url"]["pics"]."/fatcow_icons/16x16_0020/accept.png");
        $tpl->reg("icon_error", $this->config["url"]["pics"]."/fatcow_icons/16x16_0400/error.png");
        $tpl->reg("percent", $array["percent"]);
        $tpl->reg("progressbar_width", $array["percent"] * 1.5);
        
        if($array["percent"] == 100){
            $tpl->reg("buttonname", "done !");
            $tpl->reg("button_link", "");
        }else{
            $tpl->reg("buttonname", "install");
            $tpl->reg("button_link", $this->buildLink("1"));
        }
       
        if($array["fab_tagungen"] == "done" & $array["fab_teilnehmer"] == "done"){
            $tpl->setIfVar("addDemoData");
            if($array["demoEvent"] == "done"){
                $tpl->setIfVar("demodata_event");
                $tpl->reg("class_demodata_event", $array["demoEvent"]);
                $tpl->reg("buttonname_demo", "done !");
                $tpl->reg("button_link_demo", "");
            }

            if($array["demoParticipants"] == "done"){
                $tpl->setIfVar("demodata_participants");
                $tpl->reg("class_demodata_participants", $array["demoParticipants"]);
                $tpl->reg("buttonname_demo", "done !");
                $tpl->reg("button_link_demo", "");
            }

            if($array["demoParticipants"] == "error" ||  $array["demoEvent"] == "error"){
                $tpl->reg("buttonname_demo", "install");
                $tpl->reg("button_link_demo", $this->buildLink("2"));
            };
        }
        
        $tpl->reg("class_laender", $array["fab_laender"]);
        $tpl->reg("class_import", $array["fab_laender_import"]);
        $tpl->reg("class_tagungen", $array["fab_tagungen"]);
        $tpl->reg("class_teilnehmer", $array["fab_teilnehmer"]);
        $tpl->reg("class_text", $array["fab_text"]);
        
        return $tpl->parse();
    }
    
    public function setParameter($param) {
        $parts = explode("&", $param);
        foreach ($parts as $part) {
            $sub = explode("=", $part);
            $this->params[$sub[0]] = $sub[1];
        }
    }
}