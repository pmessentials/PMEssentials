<?php

namespace pmessentials\PMEssentials\event;

use pmessentials\PMEssentials\TeleportRequest;
use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class TeleportRequestEvent extends PlayerCommandEvent implements Cancellable{

    protected $request;

    public function __construct(Player $player, CommandSender $sender, TeleportRequest $request){
        parent::__construct($player, $sender);
        $this->request = $request;
    }

    public function getRequest() : TeleportRequest{
        return $this->request;
    }

    public function setRequest(TeleportRequest $request) : void{
        $this->request = $request;
    }
}