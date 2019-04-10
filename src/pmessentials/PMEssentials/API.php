<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use dominate\Command;
use pmessentials\PMEssentials\Main;
use pmessentials\PMEssentials\module\ModuleManager;
use pmessentials\PMEssentials\module\PowertoolModule;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class API{

    private $plugin;
    private static $instance;

    private function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function matchNicknames(string $partialName, CommandSender $sender = null) : array{
        $partialName = strtolower(TextFormat::clean($partialName));
        $matchedPlayers = [];
        $players = $this->plugin->getServer()->getOnlinePlayers();
        if($sender !== null && $sender->hasPermission(Main::PERMISSION_PREFIX."vanish.see")){
            array_merge($players, $this->getVanishedPlayers());
        }
        foreach($players as $player){
            if(strtolower($player->getDisplayName()) === $partialName){
                $matchedPlayers = [$player];
                break;
            }elseif(stripos(strtolower($player->getDisplayName()), $partialName) !== false){
                $matchedPlayers[] = $player;
            }
        }

        return $matchedPlayers;
    }

    /**
     * @param string $partialName
     * @param CommandSender $sender
     *
     * @return Player[]
     */
    public function matchPlayer(string $partialName, CommandSender $sender = null) : array{
        $partialName = strtolower($partialName);
        $matchedPlayers = [];
        $players = $this->plugin->getServer()->getOnlinePlayers();
        if($sender !== null && $sender->hasPermission(Main::PERMISSION_PREFIX."vanish.see")){
            array_merge($players, $this->getVanishedPlayers());
        }
        foreach($players as $player){
            if($player->getLowerCaseName() === $partialName){
                $matchedPlayers = [$player];
                break;
            }elseif(stripos($player->getName(), $partialName) !== false){
                $matchedPlayers[] = $player;
            }
        }

        return $matchedPlayers;
    }

    public static function getAPI() : API{
        if (self::$instance == null)
        {
            self::$instance = new API(Main::getInstance());
        }

        return self::$instance;
    }

    public function getPlugin() : Main{
        return $this->plugin;
    }

    public function getModuleManager() : ModuleManager{
        return $this->plugin->moduleManager;
    }

    public function getCommandMap() : EssentialsCommandMap{
        return $this->plugin->commandMap;
    }

    public function getUserMap() : UserMap{
        return $this->plugin->getUserMap();
    }

    # vanish API
    public function getVanishedPlayers() : array {
        $array = [];
        foreach($this->plugin->getUserMap()->getUsers() as $user){
            if($user->isVanished()){
                $array[$user->getPlayer()->getName()] = $user->getPlayer();
            }
        }
        return $array;
    }

    public function getVanishedPlayer(string $name) : ?Player{
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

    # powertool API
    public function disablePowertool(Item $item) : item{
        $nbt = $item->getNamedTag();
        $nbt->removeTag("powertool");
        $item->setNamedTag($nbt);
        return $item;
    }

    public function enablePowertool(item $item, string $command) : item{
        $nbt = $item->getNamedTag();
        $nbt->setString("powertool", $command, true);
        $item->setNamedTag($nbt);

        return $item;
    }

    public function isPowertool(item $item) : bool{
        $nbt = $item->getNamedTag();
        return $nbt->hasTag("powertool", StringTag::class);
    }

    public function checkCommand(item $item) : string{
        $nbt = $item->getNamedTag();
        return $nbt->getString("powertool");
    }

    # godmode API
    public function setGodmode(Player $player, bool $godmode = true){
        $this->plugin->getUserMap()->fromPlayer($player)->setGodmode($godmode);
    }

    public function isGodmode(Player $player) : bool{
        return $this->plugin->getUserMap()->fromPlayer($player)->isGodmode();
    }

    # tpa api
    public function sendTeleportRequest(Player $player, TeleportRequest $request) : void{
        $this->plugin->getUserMap()->fromPlayer($player)->sendTeleportRequest($request);
    }

    public function hasTeleportRequest(Player $player) : bool{
        return $this->plugin->getUserMap()->fromPlayer($player)->hasTeleportRequest();
    }

    public function getTeleportRequest(Player $player) : TeleportRequest{
        return $this->plugin->getUserMap()->fromPlayer($player)->getTeleportRequest();
    }

    public function isMuted(Player $player) : bool{
        return $this->plugin->getUserMap()->fromPlayer($player)->isMuted();
    }

    public function setMuted(Player $player, bool $mute) : void{
        $this->plugin->getUserMap()->fromPlayer($player)->setMuted($mute);
    }

    public function mute(Player $player) : void{
        $this->plugin->getUserMap()->fromPlayer($player)->mute();
    }

    public function unmute(Player $player) : void{
        $this->plugin->getUserMap()->fromPlayer($player)->unmute();
    }
}