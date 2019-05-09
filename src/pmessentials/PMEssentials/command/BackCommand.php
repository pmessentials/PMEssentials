<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\event\PlayerBackEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BackCommand extends SimpleExecutor {

    protected $cooldown = [];
    protected $wait = 60;

    public function __construct(){
        parent::__construct();
        $this->name = "back";
        $this->description = "teleport to your previous position";
        $this->permission = Main::PERMISSION_PREFIX."back";
        $this->usage = "/back";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        $this->wait = $this->plugin->config->get("back.cooldown");
        $t = microtime(true);

        if (isset($this->cooldown[$sender->getName()]) && $this->cooldown[$sender->getName()] + $this->wait > $t && !$sender->hasPermission(Main::PERMISSION_PREFIX."back.instant")) {
            $min = (int)floor(($this->cooldown[$sender->getName()] + $this->wait - $t)/60);
            if($min == 0){
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }else{
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c" . $min . "&4 minutes and &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }
            return true;
        }

        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Sender needs to be a player."));
            return true;
        }

        $user = $this->api->getUserMap()->fromPlayer($sender);
        if($user->getLastPos() === null){
            $sender->sendMessage(TextFormat::colorize("&4You have no previous position to go to!"));
            return true;
        }
        $ev = new PlayerBackEvent($sender, $user->getLastPos());
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        if (!$sender->hasPermission(Main::PERMISSION_PREFIX."back.instant")) {
            $this->cooldown[$sender->getName()] = $t;
        }

        $sender->teleport($ev->getPosition());
        if($sender->getLevel() !== $ev->getPosition()->getLevel()){
            $sender->setLevel($ev->getPosition()->getLevel());
        }
        $sender->sendMessage(TextFormat::colorize("&6Teleporting to your previous position..."));
        return true;
    }
}