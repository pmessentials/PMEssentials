<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\command\NickCommand;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public $api;

	public function onEnable() : void{
	    $this->api = new API($this);

        $nick = new PluginCommand("nick", $this);
        $nick->setExecutor(new NickCommand($this, $this->api));
        $nick->setDescription("change your nickname");
        $nick->setPermission("pmessentials.nick");
        $nick->setAliases(["name", "nickname"]);
        $nick->setUsage("/nick {nick} [player]");
        $this->getServer()->getCommandMap()->register("pmessentials", $nick, "nick");
	}

	public function onDisable() : void{
	}
}