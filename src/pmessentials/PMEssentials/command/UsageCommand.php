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

class UsageCommand extends SimpleExecutor {

    public function __construct(){
        parent::__construct();
        $this->name = "usage";
        $this->description = "Check a command's usage";
        $this->permission = Main::PERMISSION_PREFIX."usage";
        $this->aliases = ["howtouse"];
        $this->usage = "/usage <command>";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(!isset($args[0])){
            $sender->sendMessage(TextFormat::colorize("&4Please enter a command to see the usage of"));
            return true;
        }
        if($args[0][0] === "/"){
            $str = substr($args[0], 1);
        }else{
            $str = $args[0];
        }
        $cmap = $this->plugin->getServer()->getCommandMap();
        $cmd = $cmap->getCommand($str);
        if(!isset($cmd)){
            $sender->sendMessage(TextFormat::colorize("&4No command with name &c".$args[0]."&4 found."));
            return true;
        }
        $sender->sendMessage(TextFormat::colorize("&6Usage: &c".$cmd->getUsage()));
        return true;
    }
}