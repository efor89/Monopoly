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
			$this->saveResource('player.yml');
        }
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
	
	public static function getInstance(){
        return self::$instance;
    }
	
	public function getZufall1(){
		return mt_rand(1, 6);
	}
	
	public function getZufall2(){
		return mt_rand(1, 6);
	}
	
	public function isPasch(){
		if($this->getZufall1() == $this->getZufall2()){
			$pasch = true;
		}else{
			$pasch = false;
		}
		return $pasch;
	}
}