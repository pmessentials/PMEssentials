<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerFlyEvent;
use pmessentials\PMEssentials\event\PlayerGodmodeEvent;
use pmessentials\PMEssentials\event\PlayerNukeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\level\Explosion;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class NukeCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0]) && $sender->hasPermission(Main::PERMISSION_PREFIX."nuke.other")){
            $match = $this->plugin->getServer()->matchPlayer($args[0]);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }elseif(isset($args[1])){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to nuke someone else!"));
            return true;
        }else{
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        $ev = new PlayerNukeEvent($player, $sender, $player->getPosition());
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $explosion = new Explosion($ev->getPosition(), 100, Block::TNT);
        $explosion->explodeA();
        $explosion->explodeB();
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6You nuked yourself."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6You nuked player &c".$player->getName()."&6."));
        }
        return true;
    }
}