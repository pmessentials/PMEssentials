<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\module;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerVanishEvent;
use pmessentials\PMEssentials\listener\VanishListener;
use pmessentials\PMEssentials\Main;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class VanishModule extends ModuleBase {

    private $vanished = [];

    public function __construct(Main $plugin){
        parent::__construct($plugin);
        $this->name = self::class;
    }

    public function onStart() : void{
        $this->plugin->listeners["VanishListener"] = new VanishListener();
    }

    public function getVanishedPlayers() : array {
        return $this->vanished;
    }

    public function getVanishedPlayer(string $name) : Player {
        return $this->vanished[$name] ?? null;
    }

    public function setVanished(Player $player, bool $bool = true) : void{
        $ev = new PlayerVanishEvent($player, $bool);
        $ev->call();
        if($ev->isCancelled()) {
            return;
        }elseif($ev->getBool()){
            $this->vanished[$player->getName()] = $player;
            $this->plugin->getServer()->removeOnlinePlayer($player);
            foreach($this->plugin->getServer()->getLoggedInPlayers() as $target){
                if(!$target->hasPermission("pmessentials.vanish.see")){
                    $target->hidePlayer($player);
                }
            }
        }else{
            unset($this->vanished[$player->getName()]);
            $this->plugin->getServer()->addOnlinePlayer($player);
            foreach($this->plugin->getServer()->getLoggedInPlayers() as $target){
                if(!$target->canSee($player)){
                    $target->showPlayer($player);
                }
            }
        }
    }

    public function isVanished(Player $player) : bool{
        return isset($this->vanished[$player->getName()]);
    }

    public function vanish(Player $player) : void{
        $this->setVanished($player, true);
    }

    public function unvanish(Player $player) : void{
        $this->setVanished($player, false);
    }


}