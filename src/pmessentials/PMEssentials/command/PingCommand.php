<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\block\BlockFactory;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PingCommand extends SimpleExecutor {

    public function __construct(){
        parent::__construct();
        $this->name = "ping";
        $this->description = "Pong!";
        $this->permission = Main::PERMISSION_PREFIX."ping";
        $this->usage = "/ping";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        $sender->sendMessage(TextFormat::colorize("&6Pong!"));
        return true;
    }
}