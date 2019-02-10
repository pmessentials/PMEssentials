<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\command\FeedCommand;
use pmessentials\PMEssentials\command\GameModeCommand;
use pmessentials\PMEssentials\command\HealCommand;
use pmessentials\PMEssentials\command\NickCommand;
use pocketmine\command\PluginCommand;
use pocketmine\GameMode;
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

        $heal = new PluginCommand("heal", $this);
        $heal->setExecutor(new HealCommand($this, $this->api));
        $heal->setDescription("heal a player");
        $heal->setPermission("pmessentials.heal");
        $heal->setUsage("/heal [player]");
        $this->getServer()->getCommandMap()->register("pmessentials", $heal, "heal");

        $feed = new PluginCommand("feed", $this);
        $feed->setExecutor(new FeedCommand($this, $this->api));
        $feed->setDescription("feed a player");
        $feed->setPermission("pmessentials.feed");
        $feed->setUsage("/feed [player]");
        $this->getServer()->getCommandMap()->register("pmessentials", $feed, "feed");

        $pmgm = $this->getServer()->getCommandMap()->getCommand("gamemode");
        $this->getServer()->getCommandMap()->unregister($pmgm);

        $gm = new PluginCommand("gamemode", $this);
        $gm->setExecutor(new GameModeCommand($this, $this->api));
        $gm->setDescription("change your gamemode");
        $gm->setPermission("pmessentials.gamemode");
        $gm->setAliases(["gm", "gms", "gmc", "gma", "gmspc", "gmv"]);
        $gm->setUsage("/gamemode {mode} [player]");
        $this->getServer()->getCommandMap()->register("pmessentials", $gm, "gamemode");
	}

	public function onDisable() : void{
	}
}