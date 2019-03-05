<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BackCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Sender needs to be a player."));
            return true;
        }

        $user = $this->api->getUserMap()->fromPlayer($sender);
        if($user->getLastPos() === null){
            $sender->sendMessage(TextFormat::colorize("&4You have no previous position to go to!"));
            return true;
        }
        $sender->teleport($user->getLastPos());
        if($sender->getLevel() !== $user->getLastPos()->getLevel()){
            $sender->setLevel($user->getLastPos()->getLevel());
        }
        $sender->sendMessage(TextFormat::colorize("&6Teleporting to your previous position..."));
        return true;
    }
}