<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\Main;
use pocketmine\Player;


class UserMap{

    protected $plugin;
    protected $api;

    protected $users = [];

    protected $globalExtensions = [];

    public function __construct(){
        $this->api = API::getAPI();
        $this->plugin = $this->api->getPlugin();
    }

    public function addUser(User $user) : void{
        $user->setUserMap($this);
        $this->users[$user->getPlayer()->getName()] = $user;
    }

    public function removeUser(User $user) : void{
        unset($this->users[$user->getPlayer()->getName()]);
    }

    public function getUser(string $name) : User{
        return $this->users[$name];
    }

    public function fromPlayer(Player $player) : User{
        return $this->users[$player->getName()];
    }

    public function getUsers() : array {
        return $this->users;
    }
}