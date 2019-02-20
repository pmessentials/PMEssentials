<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ICommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4You can't execute this command from the console."));
            return true;
        }
        if(isset($args[0])){
            try{
                $item = ItemFactory::fromString($args[0]);
            }catch(\InvalidArgumentException $e){
                $sender->sendMessage(TextFormat::colorize("&4Please enter a valid item to give"));
                return true;
            }
        }else{
            $sender->sendMessage(TextFormat::colorize("&4Please enter a valid item to give"));
            return true;
        }
        if(isset($args[1]) && is_int((int)$args[1])){
            $count = (int)$args[1];
            $item->setCount(abs($count));
        }
        $sender->getInventory()->addItem($item);
        $sender->sendMessage(TextFormat::colorize("&6Added &c".$item->getCount()." ".$item->getName()."&6 to your inventory."));
        return true;
    }
}