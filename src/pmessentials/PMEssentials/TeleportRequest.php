<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\Main;
use pocketmine\Player;


class TeleportRequest{

    protected $target;
    protected $sender;
    protected $sent;
    protected $tphere;

    protected $main;
    protected $api;

    public function __construct(Player $target, Player $sender, float $sent, bool $tphere = false){
        $this->target = $target;
        $this->sender = $sender;
        $this->sent = $sent;
        $this->tphere = $tphere;
        $this->api = API::getAPI();
        $this->main = $this->api->getPlugin();
    }

    public function getTarget() : Player{
        return $this->target;
    }

    public function getSender() : Player{
        return $this->sender;
    }

    public function isTphere() : bool{
        return $this->tphere;
    }

    public function sendTime() : float {
        return $this->sent;
    }

    public function hasExpired() : bool {
        if($this->main->getServer()->getPlayer($this->sender->getName()) === null){
            return true;
        }
        return $this->sendTime() + $this->main->config->get("tpa.timeout") < microtime(true);
    }

    public function teleport() : void{
        if($this->tphere){
            $this->target->teleport($this->sender);
        }else{
            $this->sender->teleport($this->target);
        }
    }
}