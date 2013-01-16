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

        if($this->params['showui']){
            switch ($this->request->getInt("install")) {
                case 1:
                    $installer = new \Fab\Services\TableInstaller\tableInstaller($this->db);
                    $installer->execute();
                    $this->pageReload($this->config["url"]["client"]."index.php?index=".$this->request->getInt("index"));
                    break;

                default:
                    return $this->buildTemplate($this->checkTableExistance());
                    break;
            }
        }else{
            $installer = new \Fab\Services\TableInstaller\tableInstaller($this->db);
            $installer->execute();
            if($installer) return "ERLEDIGT";
        }
    }
    
    
    function buildLink($bool=false)
    {
        $array['index'] = $this->request->getInt("index");
        if($bool) $array['install'] = $bool;
        $url = $this->config["url"]["client"]."index.php?".http_build_query($array);
        $url = str_replace("&amp;", "&", $url);
        return $url;
    }
    
    public function buildTemplate($array)
    {
        #print_r($tableExistance);die();
        $template = file_get_contents(dirname(__FILE__) . '/templates/tableinstaller.tpl.html');
        $tpl = new lw_te($template);
        $tpl->reg("icon_done", $this->config["url"]["pics"]."/fatcow_icons/16x16_0020/accept.png");
        $tpl->reg("icon_error", $this->config["url"]["pics"]."/fatcow_icons/16x16_0400/error.png");
        $tpl->reg("percent", $array["percent"] / 1.5);
        $tpl->reg("progressbar_width", $array["percent"]);
        
        if($array["percent"] / 1.5 == 100){
            $tpl->reg("buttonname", "done");
            $tpl->reg("button_link", "");
        }else{
            $tpl->reg("buttonname", "install");
            $tpl->reg("button_link", $this->buildLink("1"));
        }
       
        $tpl->reg("class_laender", $array["fab_laender"]);
        $tpl->reg("class_import", $array["fab_laender"]);
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
    
    public function checkTableExistance()
    {
        $i = 0;
        if($this->db->tableExists($this->db->gt("fab_laender"))){
            $laender = "done";
            $i++;
        }else{
            $laender = "";
        }
        if($this->db->tableExists($this->db->gt("fab_tagungen"))){
            $tagungen = "done";
            $i++;
        }else{
            $tagungen = "";
        }
        if($this->db->tableExists($this->db->gt("fab_teilnehmer"))){
            $teilnehmer = "done";
            $i++;
        }else{
            $teilnehmer = "";
        }
        if($this->db->tableExists($this->db->gt("fab_text"))){
            $text = "done";
            $i++;
        }else{
            $text = "";
        }
        
        $percent = $i / 4 * 100 * 1.5;
        
        return array(
            "fab_laender" => $laender,
            "fab_tagungen" => $tagungen,
            "fab_teilnehmer" => $teilnehmer,
            "fab_text" => $text,
            "percent" => $percent
        );
    }
}