<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandMap;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class UsageCommand extends Command {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(!isset($args[0])){
            $sender->sendMessage(TextFormat::colorize("&4Please enter a command to see the usage of"));
            return true;
        }
        $cmap = $this->plugin->getServer()->getCommandMap();
        $cmd = $cmap->getCommand($args[0]);
        if(!isset($cmd)){
            $sender->sendMessage(TextFormat::colorize("&4No command with name &c".$args[0]."&4 found."));
            return true;
        }
        $sender->sendMessage(TextFormat::colorize("&6Usage: &c".$cmd->getUsage()));
        return true;
    }
}