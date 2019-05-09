<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\BroadcastEvent;
use pmessentials\PMEssentials\event\PlayerBurnEvent;
use pmessentials\PMEssentials\event\PlayerExtinguishEvent;
use pmessentials\PMEssentials\event\PlayerSizeChangeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BroadcastCommand extends SimpleExecutor {

    public function __construct(){
        parent::__construct();
        $this->name = "broadcast";
        $this->description = "broadcast a message";
        $this->permission = Main::PERMISSION_PREFIX."broadcast";
        $this->aliases = ["bc", "bcast"];
        $this->usage = "/broadcast <message>";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {

        if(!isset($args[0])){
            $sender->sendMessage(TextFormat::colorize("&4Please enter a message!"));
            return true;
        }
        $str = implode(" ", $args);

        $ev = new BroadcastEvent($sender, $str);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $this->plugin->getServer()->broadcastMessage(TextFormat::colorize($ev->getMessage()));
        return true;
    }
}