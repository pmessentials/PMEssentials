<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\Main;
use pmessentials\PMEssentials\module\ModuleManager;
use pmessentials\PMEssentials\module\PowertoolModule;
use pocketmine\utils\TextFormat;

class API{

    private $plugin;
    private static $instance;

    private function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function matchNicknames(string $partialName) : array{
        $partialName = strtolower(TextFormat::clean($partialName));
        $matchedPlayers = [];
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
            if(strtolower($player->getDisplayName()) === $partialName){
                $matchedPlayers = [$player];
                break;
            }elseif(stripos(strtolower($player->getDisplayName()), $partialName) !== false){
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
}