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

    public function __construct(Main $plugin){
        parent::__construct($plugin);
        $this->name = self::class;
    }

    public function onStart() : void{
        $this->plugin->listeners["VanishListener"] = new VanishListener();
    }

    public function getVanishedPlayers() : array {
        $array = [];
        foreach($this->plugin->getUserMap()->getUsers() as $user){
            if($user->isVanished()){
                $array[$user->getPlayer()->getName()] = $user;
            }
        }
        return $array;
    }

    public function getVanishedPlayer(string $name) : Player {
        $array = $this->getVanishedPlayers();
        return $array[$name] ?? null;

    }

    public function setVanished(Player $player, bool $bool = true) : void{
        $this->plugin->getUserMap()->fromPlayer($player)->setVanished($bool);
    }

    public function isVanished(Player $player) : bool{
        return $this->plugin->getUserMap()->fromPlayer($player)->isVanished();
    }

    public function vanish(Player $player) : void{
        $this->setVanished($player, true);
    }

    public function unvanish(Player $player) : void{
        $this->setVanished($player, false);
    }


}