<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PowertoolCommand extends SimpleExecutor {

    public function __construct(){
        parent::__construct();
        $this->name = "powertool";
        $this->description = "Assign a command to an item";
        $this->permission = Main::PERMISSION_PREFIX."powertool.set";
        $this->aliases = ["pt"];
        $this->usage = "/pt <command>";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        $item = $sender->getInventory()->getItemInHand();

        if(!isset($args[0]) && $this->api->isPowertool($item)){
            $disabledItem = $this->api->disablePowertool($item);
            $sender->getInventory()->setItemInHand($disabledItem);
            $sender->sendMessage(TextFormat::colorize("&6Powertool unassigned."));
        }elseif(isset($args[0]) && !$this->api->isPowertool($item)){
            $powertool = $this->api->enablePowertool($item, implode(" ", $args));
            $sender->getInventory()->setItemInHand($powertool);
            $str = implode(" ", $args);
            $sender->sendMessage(TextFormat::colorize("&6Assigned &c/".$str."&r&6 to this item."));
        }elseif($this->api->isPowertool($item)){
            $sender->sendMessage(TextFormat::colorize("&4A command has already been assigned to this item!"));
        }else{
            $sender->sendMessage(TextFormat::colorize("&4Please enter a command to assign!"));
        }
        return true;
    }
}