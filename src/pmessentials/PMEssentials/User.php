<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\event\PlayerGodmodeEvent;
use pmessentials\PMEssentials\event\PlayerVanishEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;


class User{
    protected $api;
    protected $plugin;

    protected $player;
    protected $map;
    public $data = []; //data for any plugin to access

    /** @var TeleportRequest */
    protected $request;
    protected $vanish = false;
    protected $godmode = false;
    /** @var Position */
    protected $lastpos;

    public function getUserMap() : UserMap{
        return $this->map;
    }

    public function setUserMap(UserMap $map) : void{
        $this->map = $map;
    }

    public function __construct(Player $player){
        $this->player = $player;
        $this->api = API::getAPI();
        $this->plugin = $this->api->getPlugin();
    }

    public function getPlayer() : Player{
        return $this->player;
    }

    protected function setPlayer(Player $player) : void{
        $this->player = $player;
    }

    public function save() : void{

    }

    public function load() : void{

    }

    public function setVanished(bool $bool = true) : void{
        if($bool){
            $this->vanish = true;
            $this->plugin->getServer()->removeOnlinePlayer($this->getPlayer());
            foreach($this->plugin->getServer()->getLoggedInPlayers() as $target){
                if(!$target->hasPermission(Main::PERMISSION_PREFIX."vanish.see")){
                    $target->hidePlayer($this->getPlayer());
                }
            }
        }else{
            $this->vanish = false;
            $this->plugin->getServer()->addOnlinePlayer($this->getPlayer());
            foreach($this->plugin->getServer()->getLoggedInPlayers() as $target){
                if(!$target->canSee($this->getPlayer())){
                    $target->showPlayer($this->getPlayer());
                }
            }
        }
    }

    public function isVanished() : bool{
        return $this->vanish;
    }

    public function setGodmode(bool $bool = true) : void{
        $this->godmode = $bool;
    }

    public function isGodmode() : bool{
        return $this->godmode;
    }

    public function sendSilentTeleportRequest(TeleportRequest $request) : void{
        $this->request = $request;
    }

    public function getTeleportRequest() : TeleportRequest{
        return $this->request;
    }

    public function hasTeleportRequest() : bool{
        if(isset($this->request)){
            return !$this->request->hasExpired();
        }else{
            return false;
        }
    }

    public function sendTeleportRequest(TeleportRequest $request) : void{
        $this->sendSilentTeleportRequest($request);
        if($request->isTphere()){
            $this->getPlayer()->sendMessage(TextFormat::colorize("&c".$request->getSender()->getName().
                " &6has requested you to teleport to them.\n&6To teleport, type &c/tpaccept&6.\n&6To deny this request, type &c/tpdeny&6.\n&6This request will timeout after &c".
                $this->plugin->config->get("tpa.timeout")." seconds&6."));
        }else{
            $this->getPlayer()->sendMessage(TextFormat::colorize("&c".$request->getSender()->getName().
                " &6has requested to teleport to you.\n&6To teleport, type &c/tpaccept&6.\n&6To deny this request, type &c/tpdeny&6.\n&6This request will timeout after &c".
                $this->plugin->config->get("tpa.timeout")." seconds&6."));
        }
    }

    public function acceptRequest() : bool {
        if($this->hasTeleportRequest()){
            $this->request->teleport();
            $this->request->getSender()->sendMessage(TextFormat::colorize("&6Your teleport request has been accepted."));
            unset($this->request);
            return true;
        }else{
            return false;
        }
    }

    public function denyRequest() : bool{
        $bool = $this->hasTeleportRequest();
        if(isset($this->request)){
            $this->request->getSender()->sendMessage(TextFormat::colorize("&6Your teleport request has been denied."));
            unset($this->request);
        }
        return $bool;
    }

    public function getLastPos() : ?Position{
        return $this->lastpos ?? null;
    }

    public function setLastPos(Position $pos) : void{
        $this->lastpos = $pos;
    }
}