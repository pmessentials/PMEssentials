<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\Main;

class API{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
}