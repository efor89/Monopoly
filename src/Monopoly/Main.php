<?php

declare(strict_types=1);

namespace Monopoly;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Monopoly\aktionen\Abbauen;
use Monopoly\aktionen\Anmelden;
use Monopoly\aktionen\AufgebenJa;
use Monopoly\aktionen\AufgebenMain;
use Monopoly\aktionen\AufgebenNein;
use Monopoly\aktionen\BauenMain;
use Monopoly\aktionen\FreiKaufen;
use Monopoly\aktionen\Handeln;
use Monopoly\aktionen\HausBauen;
use Monopoly\aktionen\HotelBauen;
use Monopoly\aktionen\Hypothek;
use Monopoly\aktionen\Infos;
use Monopoly\aktionen\Kaufen;
use Monopoly\aktionen\MieteBezahlen;
use Monopoly\aktionen\Start;
use Monopoly\aktionen\Wuerfeln;
use Monopoly\aktionen\ZugBeenden;
use Monopoly\aktionen\Zurueck;
use Monopoly\ui\Ereigniskarte;
use Monopoly\ui\Gemeinschaftskarte;
use Monopoly\ui\Hypothek;

class Main extends PluginBase{
	
	public static $instance;
	
	protected $ereignis;
	
	protected $gemeinschaft;
	
	protected $hypothek;

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
		$this->getServer()->getPluginManager()->registerEvents(new Abbauen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Anmelden($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new AufgebenJa($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new AufgebenMain($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new AufgebenNein($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new BauenMain($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new FreiKaufen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Handeln($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new HausBauen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new HotelBauen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Hypothek($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Infos($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Kaufen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new MieteBezahlen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Start($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Wuerfeln($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new ZugBeenden($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Zurueck($this), $this);
		
		$this->ereignis = new Ereigniskarte($this);
		$this->gemeinschaft = new Gemeinschaftskarte($this);
		$this->hypothek = new Hypothek($this);
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
		$gamecfg->set("miete", false);
		$gamecfg->set("freiparken", 0);
		$gamecfg->set("knast-turn1", 0);
		$gamecfg->set("knast-turn2", 0);
		$gamecfg->set("knast-turn3", 0);
		$gamecfg->set("knast-turn4", 0);
		$gamecfg->set("knast1", false);
		$gamecfg->set("knast2", false);
		$gamecfg->set("knast3", false);
		$gamecfg->set("knast4", false);
		$gamecfg->set("2", null);
		$gamecfg->set("4", null);
		$gamecfg->set("6", null);
		$gamecfg->set("7", null);
		$gamecfg->set("9", null);
		$gamecfg->set("10", null);
		$gamecfg->set("12", null);
		$gamecfg->set("13", null);
		$gamecfg->set("14", null);
		$gamecfg->set("15", null);
		$gamecfg->set("16", null);
		$gamecfg->set("17", null);
		$gamecfg->set("19", null);
		$gamecfg->set("20", null);
		$gamecfg->set("22", null);
		$gamecfg->set("24", null);
		$gamecfg->set("25", null);
		$gamecfg->set("26", null);
		$gamecfg->set("27", null);
		$gamecfg->set("28", null);
		$gamecfg->set("29", null);
		$gamecfg->set("30", null);
		$gamecfg->set("32", null);
		$gamecfg->set("33", null);
		$gamecfg->set("35", null);
		$gamecfg->set("36", null);
		$gamecfg->set("38", null);
		$gamecfg->set("40", null);
		$gamecfg->save();
	}
	
	function getGemeinschaft() {
        return $this->gemeinschaft;
    }
	
	function getEreignis() {
        return $this->ereignis;
    }
	
	function getHypothek() {
        return $this->hypothek;
    }
	
	public function getZufall1(){
		return mt_rand(1, 6);
	}
	
	public function getZufall2(){
		return mt_rand(1, 6);
	}
	
	public function removeCarts(Player $player){
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		if($gamecfg->get("2") == $player->getName()){
			$gamecfg->set("2", null);
			$gamecfg->save();
		}
		if($gamecfg->get("4") == $player->getName()){
			$gamecfg->set("4", null);
			$gamecfg->save();
		}
		if($gamecfg->get("6") == $player->getName()){
			$gamecfg->set("6", null);
			$gamecfg->save();
		}
		if($gamecfg->get("7") == $player->getName()){
			$gamecfg->set("7", null);
			$gamecfg->save();
		}
		if($gamecfg->get("9") == $player->getName()){
			$gamecfg->set("9", null);
			$gamecfg->save();
		}
		if($gamecfg->get("10") == $player->getName()){
			$gamecfg->set("10", null);
			$gamecfg->save();
		}
		if($gamecfg->get("12") == $player->getName()){
			$gamecfg->set("12", null);
			$gamecfg->save();
		}
		if($gamecfg->get("13") == $player->getName()){
			$gamecfg->set("13", null);
			$gamecfg->save();
		}
		if($gamecfg->get("14") == $player->getName()){
			$gamecfg->set("14", null);
			$gamecfg->save();
		}
		if($gamecfg->get("15") == $player->getName()){
			$gamecfg->set("15", null);
			$gamecfg->save();
		}
		if($gamecfg->get("16") == $player->getName()){
			$gamecfg->set("16", null);
			$gamecfg->save();
		}
		if($gamecfg->get("17") == $player->getName()){
			$gamecfg->set("17", null);
			$gamecfg->save();
		}
		if($gamecfg->get("19") == $player->getName()){
			$gamecfg->set("19", null);
			$gamecfg->save();
		}
		if($gamecfg->get("20") == $player->getName()){
			$gamecfg->set("20", null);
			$gamecfg->save();
		}
		if($gamecfg->get("22") == $player->getName()){
			$gamecfg->set("22", null);
			$gamecfg->save();
		}
		if($gamecfg->get("24") == $player->getName()){
			$gamecfg->set("24", null);
			$gamecfg->save();
		}
		if($gamecfg->get("25") == $player->getName()){
			$gamecfg->set("25", null);
			$gamecfg->save();
		}
		if($gamecfg->get("26") == $player->getName()){
			$gamecfg->set("26", null);
			$gamecfg->save();
		}
		if($gamecfg->get("27") == $player->getName()){
			$gamecfg->set("27", null);
			$gamecfg->save();
		}
		if($gamecfg->get("28") == $player->getName()){
			$gamecfg->set("28", null);
			$gamecfg->save();
		}
		if($gamecfg->get("29") == $player->getName()){
			$gamecfg->set("29", null);
			$gamecfg->save();
		}
		if($gamecfg->get("30") == $player->getName()){
			$gamecfg->set("30", null);
			$gamecfg->save();
		}
		if($gamecfg->get("32") == $player->getName()){
			$gamecfg->set("32", null);
			$gamecfg->save();
		}
		if($gamecfg->get("33") == $player->getName()){
			$gamecfg->set("33", null);
			$gamecfg->save();
		}
		if($gamecfg->get("35") == $player->getName()){
			$gamecfg->set("35", null);
			$gamecfg->save();
		}
		if($gamecfg->get("36") == $player->getName()){
			$gamecfg->set("36", null);
			$gamecfg->save();
		}
		if($gamecfg->get("38") == $player->getName()){
			$gamecfg->set("38", null);
			$gamecfg->save();
		}
		if($gamecfg->get("40") == $player->getName()){
			$gamecfg->set("40", null);
			$gamecfg->save();
		}
	}
	
	public function isFullStreet(Player $player, $feld){
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		if($feld == 2 or $feld == 4){
		    if($gamecfg->get("2") == $player->getName() and $gamecfg->get("4") == $player->getName()){
			    return "yes";
		    }
		}
		if($feld == 13 or $feld == 29){
		    if($gamecfg->get("13") == $player->getName() and $gamecfg->get("29") == $player->getName()){
			    return "yes";
		    }
		}
		if($feld == 38 or $feld == 40){
		    if($gamecfg->get("38") == $player->getName() and $gamecfg->get("40") == $player->getName()){
			    return "yes";
		    }
		}
		if($feld == 7 or $feld == 9 or $feld == 10){
		    if($gamecfg->get("7") == $player->getName() and $gamecfg->get("9") == $player->getName() and $gamecfg->get("10") == $player->getName()){
			    return "yes";
		    }
		}
		if($feld == 12 or $feld == 14 or $feld == 15){
		    if($gamecfg->get("12") == $player->getName() and $gamecfg->get("14") == $player->getName() and $gamecfg->get("15") == $player->getName()){
			    return "yes";
		    }
		}
		if($feld == 17 or $feld == 19 or $feld == 20){
		    if($gamecfg->get("17") == $player->getName() and $gamecfg->get("19") == $player->getName() and $gamecfg->get("20") == $player->getName()){
			    return "yes";
		    }
		}
		if($feld == 22 or $feld == 24 or $feld == 25){
		    if($gamecfg->get("22") == $player->getName() and $gamecfg->get("24") == $player->getName() and $gamecfg->get("25") == $player->getName()){
			    return "yes";
		    }
		}
		if($feld == 27 or $feld == 28 or $feld == 30){
		    if($gamecfg->get("27") == $player->getName() and $gamecfg->get("28") == $player->getName() and $gamecfg->get("30") == $player->getName()){
			    return "yes";
		    }
		}
		if($feld == 32 or $feld == 33 or $feld == 35){
		    if($gamecfg->get("32") == $player->getName() and $gamecfg->get("33") == $player->getName() and $gamecfg->get("35") == $player->getName()){
			    return "yes";
		    }
		}
		return "no";
	}
	
	public function getTrainCount(Player $player, $feld){
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		if($feld == 6 or $feld == 16 or $feld == 26 or $feld == 36){
		    if($gamecfg->get("6") == $player->getName() and $gamecfg->get("16") == $player->getName() and $gamecfg->get("26") == $player->getName() and $gamecfg->get("36") == $player->getName()){
			    $zahl = 4;
		    }
		    if($gamecfg->get("6") != $player->getName() and $gamecfg->get("16") == $player->getName() and $gamecfg->get("26") == $player->getName() and $gamecfg->get("36") == $player->getName()){
		    	$zahl = 3;
		    }
		    if($gamecfg->get("6") == $player->getName() and $gamecfg->get("16") != $player->getName() and $gamecfg->get("26") == $player->getName() and $gamecfg->get("36") == $player->getName()){
			    $zahl = 3;
		    }
		    if($gamecfg->get("6") == $player->getName() and $gamecfg->get("16") == $player->getName() and $gamecfg->get("26") != $player->getName() and $gamecfg->get("36") == $player->getName()){
			    $zahl = 3;
		    }
		    if($gamecfg->get("6") == $player->getName() and $gamecfg->get("16") == $player->getName() and $gamecfg->get("26") == $player->getName() and $gamecfg->get("36") != $player->getName()){
			    $zahl = 3;
		    }
		    if($gamecfg->get("6") != $player->getName() and $gamecfg->get("16") != $player->getName() and $gamecfg->get("26") != $player->getName() and $gamecfg->get("36") == $player->getName()){
			    $zahl = 1;
		    }
		    if($gamecfg->get("6") != $player->getName() and $gamecfg->get("16") != $player->getName() and $gamecfg->get("26") == $player->getName() and $gamecfg->get("36") != $player->getName()){
		    	$zahl = 1;
		    }
		    if($gamecfg->get("6") != $player->getName() and $gamecfg->get("16") == $player->getName() and $gamecfg->get("26") != $player->getName() and $gamecfg->get("36") != $player->getName()){
			    $zahl = 1;
		    }
		    if($gamecfg->get("6") == $player->getName() and $gamecfg->get("16") != $player->getName() and $gamecfg->get("26") != $player->getName() and $gamecfg->get("36") != $player->getName()){
			    $zahl = 1;
		    }
		    if($gamecfg->get("6") != $player->getName() and $gamecfg->get("16") != $player->getName() and $gamecfg->get("26") == $player->getName() and $gamecfg->get("36") == $player->getName()){
			    $zahl = 2;
		    }
		    if($gamecfg->get("6") != $player->getName() and $gamecfg->get("16") == $player->getName() and $gamecfg->get("26") != $player->getName() and $gamecfg->get("36") == $player->getName()){
			    $zahl = 2;
		    }
		    if($gamecfg->get("6") != $player->getName() and $gamecfg->get("16") != $player->getName() and $gamecfg->get("26") == $player->getName() and $gamecfg->get("36") != $player->getName()){
			    $zahl = 2;
		    }
		    if($gamecfg->get("6") == $player->getName() and $gamecfg->get("16") != $player->getName() and $gamecfg->get("26") != $player->getName() and $gamecfg->get("36") == $player->getName()){
			    $zahl = 2;
		    }
		    if($gamecfg->get("6") == $player->getName() and $gamecfg->get("16") != $player->getName() and $gamecfg->get("26") == $player->getName() and $gamecfg->get("36") != $player->getName()){
			    $zahl = 2;
		    }
		    if($gamecfg->get("6") == $player->getName() and $gamecfg->get("16") == $player->getName() and $gamecfg->get("26") != $player->getName() and $gamecfg->get("36") != $player->getName()){
		    	$zahl = 2;
		    }
		}
		return $zahl;
	}
	
	public static function getInstance(){
        return self::$instance;
    }
}