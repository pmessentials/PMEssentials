<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\Main;
use pocketmine\utils\TextFormat;

class API{

    private $plugin;
    private static $api;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        self::$api = $this;
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
        return self::$api;
    }
}