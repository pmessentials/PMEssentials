<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\GameMode;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class GameModeCommand extends Command {

    public function __construct(Main $plugin, API $api){
        parent::__construct($plugin, $api);
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        switch (strtolower($label)){
            case "gmc":
                $gm = 1;
                if(isset($args[0])){
                    $target = $args[0];
                }
                break;
            case "gms":
                $gm = 0;
                if(isset($args[0])){
                    $target = $args[0];
                }
                break;
            case "gma":
                $gm = 2;
                if(isset($args[0])){
                    $target = $args[0];
                }
                break;
            case "gmspc":
            case "gmv":
                $gm = 3;
                if(isset($args[0])){
                    $target = $args[0];
                }
                break;
            default:
                if(!isset($args[0])){
                    $sender->sendMessage(TextFormat::colorize("&4Please enter a gamemode!"));
                    return true;
                }
                if(isset($args[1])){
                    $target = $args[1];
                }
                try{
                    $gm = GameMode::fromString($args[0]);
                }catch (\InvalidArgumentException $e){
                    $gm = -1;
                }
                break;
        }

        switch ($gm){
            case 0:
                $gmstr = "survival";
                break;
            case 1:
                $gmstr = "creative";
                break;
            case 2:
                $gmstr = "adventure";
                break;
            case 3:
                $gmstr = "spectator";
                break;
            default:
            $sender->sendMessage(TextFormat::colorize("&4You have provided an invalid gamemode!"));
            return true;
            break;
        }

        if(!$sender->hasPermission("pmessentials.gamemode.".$gmstr)){
            $sender->sendMessage(TextFormat::colorize("&4You're not allowed to change someone's gamemode to &c".$gmstr."&4!"));
            return true;
        }

        if(isset($target) && $sender->hasPermission("pmessentials.gamemode.other")){
            $match = $this->plugin->getServer()->matchPlayer($target);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$target."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }else{
            $player = $sender;
        }

        $player->setGamemode($gm);
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6Your gamemode has been set to &c".$gmstr."&r&6."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Set ".$player->getName()."&6's gamemode to &r&c".$gmstr."&r&6."));
            $player->sendMessage(TextFormat::colorize("&6Your gamemode has been set to &c".$gmstr."&r&6."));
        }
        return true;
    }
}