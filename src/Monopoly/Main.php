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
	
	public function onDisable(): Void{
		$gamecfg = new Config($this->getDataFolder().'game.yml', Config::YAML);
		$players = new Config($this->getDataFolder().'player.yml', Config::YAML);
		$players->set("player1", null);
	    $players->set("player2", null);
	    $players->set("player3", null);
	    $players->set("player4", null);
	    $players->save();
		$gamecfg->set("start", false);
		$gamecfg->set("turn", null);
		$gamecfg->set("pasch", 0);
		$gamecfg->set("wurf", false);
		$gamecfg->set("knast-turn1", 0);
		$gamecfg->set("knast-turn2", 0);
		$gamecfg->set("knast-turn3", 0);
		$gamecfg->set("knast-turn4", 0);
		$gamecfg->set("knast1", false);
		$gamecfg->set("knast2", false);
		$gamecfg->set("knast3", false);
		$gamecfg->set("knast4", false);
		$gamecfg->save();
	}
	
	public static function getInstance(){
        return self::$instance;
    }
}