<?php

namespace CustomAreas;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerKickEvent;

class EventListener implements Listener{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onPlace(BlockPlaceEvent $event){
        if(!$event->getPlayer()->hasPermission("customareas.bypass")){
            foreach($this->plugin->areas as $area){
                if($area->isInside($event->getBlock()) and !$area->canBuild($event->getPlayer())){
                    $event->setCancelled();
                    $event->getPlayer()->sendMessage("This is " . $area->owner . "'s private area ID: " . $area->id);
                }
            }
        }
    }

    public function onBreak(BlockBreakEvent $event){
        if(!$event->getPlayer()->hasPermission("customareas.bypass")){
            foreach($this->plugin->areas as $area){
                if($area->isInside($event->getBlock()) and !$area->canBuild($event->getPlayer())){
                    $event->setCancelled();
                    $event->getPlayer()->sendMessage("This is " . $area->owner . "'s private area ID: " . $area->id);
                }
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event){
        if(!$event->getPlayer()->hasPermission("customareas.bypass")){
            foreach($this->plugin->areas as $area){
                if($area->isInside($event->getBlock()) and !$area->canBuild($event->getPlayer())){
                    $event->setCancelled();
                    $event->getPlayer()->sendMessage("This is " . $area->owner . "'s private area ID: " . $area->id);
                }
            }
        }
    }

    public function onPlayerMove(PlayerMoveEvent $event){
        if(!$event->getPlayer()->hasPermission("customareas.info")){
            foreach($this->plugin->areas as $area){
                if($area->isInside($event->getPlayer()->getPosition())){
                    $playerName = $event->getPlayer()->getName();
                    if(!array_key_exists($playerName, $this->plugin->activePlayers) || $this->plugin->activePlayers[$playerName] != $area->id){
                        $this->plugin->activePlayers[$playerName] = $area->id;
                        $event->getPlayer()->sendMessage("You are in " . $area->owner . "'s private area ID: " . $area->id);
                    }
                }
            }
        }
    }

    public function onPlayerKick(PlayerKickEvent $event){
        $playerName = $event->getPlayer()->getName();
        if(array_key_exists($playerName, $this->plugin->activePlayers)){
            unset($this->plugin->activePlayers[$playerName]);
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event){
        $playerName = $event->getPlayer()->getName();
        if(array_key_exists($playerName, $this->plugin->activePlayers)){
            unset($this->plugin->activePlayers[$playerName]);
        }
    }
}