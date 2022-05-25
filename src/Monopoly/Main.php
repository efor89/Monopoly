<?php

declare(strict_types=1);

namespace Monopoly;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{
	
	public static $instance;

    public function onEnable(): void{
		$this->getServer()->getLogger()->notice("§aMonopoly wurde geladen!");
        if(!file_exists($this->getDataFolder() . "monopoly.yml")){
            $this->saveResource('monopoly.yml');
        }
		if(!file_exists($this->getDataFolder() . "player.yml")){
			$this->saveResource('player.yml');
        }
		if(!file_exists($this->getDataFolder() . "game.yml")){
            $this->saveResource('game.yml');
        }
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
	
	public static function getInstance(){
        return self::$instance;
    }
}