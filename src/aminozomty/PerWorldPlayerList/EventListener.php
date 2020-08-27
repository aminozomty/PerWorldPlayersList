<?php

namespace aminozomty\PerWorldPlayersList;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\network\mcpe\protocol\types\SkinAdapterSingleton;
use pocketmine\Server;
use pocketmine\Player;

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
