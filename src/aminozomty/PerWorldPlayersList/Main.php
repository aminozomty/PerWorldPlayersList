<?php

/**
 * A PocketMine-MP plugin that makes only shows players that are in the same world in the player list menu.
 * Copyright (C) 2020 aminozomty
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace aminozomty\PerWorldPlayersList;

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
