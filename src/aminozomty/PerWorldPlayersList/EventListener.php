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

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\network\mcpe\protocol\types\SkinAdapterSingleton;

use aminozomty\PerWorldPlayersList\Main;

class EventListener implements Listener{

    public function onJoin(PlayerJoinEvent $event){
        foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $p){
            if($p->getLevel()->getName() == $event->getPlayer()->getLevel()->getName()) return;
            $entry = new PlayerListEntry();
            $entry->uuid = $event->getPlayer()->getUniqueId();
            $pk = new PlayerListPacket();
            $pk->entries[] = $entry;
            $pk->type = PlayerListPacket::TYPE_REMOVE;
            $p->sendDataPacket($pk);
            $entry = new PlayerListEntry();
            $entry->uuid = $p->getPlayer()->getUniqueId();
            $pk = new PlayerListPacket();
            $pk->entries[] = $entry;
            $pk->type = PlayerListPacket::TYPE_REMOVE;
            $player = $event->getpLAYER();
            $player->sendDataPacket($pk);
        } 
    }
    public function LevelChange(EntityLevelChangeEvent $event){
        if (!$event->getEntity() instanceof Player) return;
        foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $p){
            if($p->getLevel()->getName() == $event->getTarget()->getName()) {
                $pk = new PlayerListPacket();
                $pk->type = PlayerListPacket::TYPE_ADD;
                $player = $event->getEntity();
                $pk->entries[] = PlayerListEntry::createAdditionEntry($player->getUniqueId(), $player->getId(), $player->getDisplayName(), SkinAdapterSingleton::get()->toSkinData($player->getSkin()), $player->getXuid());
                $p->sendDataPacket($pk);
                $pk = new PlayerListPacket();
                $pk->type = PlayerListPacket::TYPE_ADD;
                $player = $p;
                $pk->entries[] = PlayerListEntry::createAdditionEntry($player->getUniqueId(), $player->getId(), $player->getDisplayName(), SkinAdapterSingleton::get()->toSkinData($player->getSkin()), $player->getXuid());
                $event->getEntity()->sendDataPacket($pk);
                continue;
            }
            $entry = new PlayerListEntry();
            $entry->uuid = $event->getEntity()->getUniqueId();
            $pk = new PlayerListPacket();
            $pk->entries[] = $entry;
            $pk->type = PlayerListPacket::TYPE_REMOVE;
            $p->sendDataPacket($pk);
            $entry = new PlayerListEntry();
            $entry->uuid = $p->getPlayer()->getUniqueId();
            $pk = new PlayerListPacket();
            $pk->entries[] = $entry;
            $pk->type = PlayerListPacket::TYPE_REMOVE;
            $player = $event->getEntity();
            $player->sendDataPacket($pk);
        }
    }
}
