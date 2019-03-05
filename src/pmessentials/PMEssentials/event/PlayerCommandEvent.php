<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

abstract class PlayerCommandEvent extends PlayerEvent implements Cancellable{

    protected $sender;

    public function __construct(Player $player, CommandSender $sender){
        $this->player = $player;
        $this->sender = $sender;
    }

    public function getSender() : CommandSender{
        return $this->sender;
    }
}