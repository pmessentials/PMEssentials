<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerMilkEvent extends PlayerCommandEvent implements Cancellable{

    public function __construct(Player $player, CommandSender $sender){
        parent::__construct($player, $sender);
    }
}