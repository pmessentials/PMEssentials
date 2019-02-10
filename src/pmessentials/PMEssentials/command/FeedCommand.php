<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class FeedCommand extends Command {

    public function __construct(Main $plugin, API $api){
        parent::__construct($plugin, $api);
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0]) && $sender->hasPermission("pmessentials.feed.other")){
            $match = $this->plugin->getServer()->matchPlayer($args[0]);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }else{
            $player = $sender;
        }
        $player->setFood($player->getMaxFood());
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6You have been fed!"));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Restored &c".$player->getName()."&r&6's food."));
            $player->sendMessage(TextFormat::colorize("&6You have been fed!"));
        }
        return true;
    }
}