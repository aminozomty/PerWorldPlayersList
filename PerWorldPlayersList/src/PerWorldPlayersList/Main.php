<?php

namespace PerWorldPlayersList;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;

class Main extends PluginBase{

    private static $instance = null;

    public function onEnable(){
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }
    public static function getInstance(){
        return self::$instance;
    }
}