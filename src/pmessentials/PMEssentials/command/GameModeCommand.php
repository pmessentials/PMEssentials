<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class GameModeCommand extends SimpleExecutor {

    public const SURVIVAL = 0;
    public const CREATIVE = 1;
    public const ADVENTURE = 2;
    public const SPECTATOR = 3;
    public const VIEW = self::SPECTATOR;

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
                    $gm = self::fromString($args[0]);
                }catch (\Exception $e){
                    $sender->sendMessage(TextFormat::colorize("&4Please enter a valid gamemode!"));
                    return true;
                }
                break;
        }

        try{
            $gmstr = self::toString($gm);
        }catch (\Exception $e){
            $sender->sendMessage(TextFormat::colorize("&4Please enter valid gamemode!"));
            return true;
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

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&6Target needs to be a player"));
            return true;
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

    //taken from 4.0.0 gamemode class to be compatible with 3.0.0
    public static function toString(int $mode) : string{
        switch($mode){
            case self::SURVIVAL:
                return "Survival";
            case self::CREATIVE:
                return "Creative";
            case self::ADVENTURE:
                return "Adventure";
            case self::SPECTATOR:
                return "Spectator";
            default:
                throw new \InvalidArgumentException("Invalid gamemode $mode");
        }
    }

    //taken from 4.0.0 gamemode class to be compatible with 3.0.0
    public static function fromString(string $str) : int{
        switch(strtolower(trim($str))){
            case (string) self::SURVIVAL:
            case "survival":
            case "s":
                return self::SURVIVAL;

            case (string) self::CREATIVE:
            case "creative":
            case "c":
                return self::CREATIVE;

            case (string) self::ADVENTURE:
            case "adventure":
            case "a":
                return self::ADVENTURE;

            case (string) self::SPECTATOR:
            case "spectator":
            case "view":
            case "v":
                return self::SPECTATOR;
        }

        throw new \InvalidArgumentException("Unknown gamemode string \"$str\"");
    }
}