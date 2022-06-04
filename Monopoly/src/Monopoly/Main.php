<?php

declare(strict_types=1);

namespace Monopoly;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use Monopoly\EventListener;
use Monopoly\aktionen\Anmelden;
use Monopoly\aktionen\AufgebenJa;
use Monopoly\aktionen\AufgebenMain;
use Monopoly\aktionen\AufgebenNein;
use Monopoly\aktionen\BauenMain;
use Monopoly\aktionen\Bieten;
use Monopoly\aktionen\FreiKaufen;
use Monopoly\aktionen\Handeln;
use Monopoly\aktionen\HausBauen;
use Monopoly\aktionen\Hausabbauen;
use Monopoly\aktionen\Hypothek;
use Monopoly\aktionen\Infos;
use Monopoly\aktionen\Kaufen;
use Monopoly\aktionen\KaufenJa;
use Monopoly\aktionen\KaufenNein;
use Monopoly\aktionen\MieteBezahlen;
use Monopoly\aktionen\NichtBieten;
use Monopoly\aktionen\Start;
use Monopoly\aktionen\Wuerfeln;
use Monopoly\aktionen\ZugBeenden;
use Monopoly\aktionen\Zurueck;
use Monopoly\ui\AbbauenUI;
use Monopoly\ui\BauenUI;
use Monopoly\ui\Ereigniskarte;
use Monopoly\ui\Gemeinschaftskarte;
use Monopoly\ui\HypothekUI;
use Monopoly\ui\HandelnMain;
use Monopoly\ui\InfoMain;

class Main extends PluginBase{
	
	public static $instance;
	
	protected $ereignis;
	
	protected $gemeinschaft;
	
	protected $hypothekui;
	
	protected $handelnmain;
	
	protected $infomain;
	
	protected $bauenui;
	
	protected $abbauenui;

    public function onEnable(): void{
		$this->getServer()->getLogger()->notice("§aDas Monopoly Plugin wurde geladen!");
        if(!file_exists($this->getDataFolder() . "monopoly.yml")){
            $this->saveResource('monopoly.yml');
			$this->saveResource('game.yml');
			$this->saveResource('player.yml');
        }
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Anmelden($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new AufgebenJa($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new AufgebenMain($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new AufgebenNein($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new BauenMain($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Bieten($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new FreiKaufen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Handeln($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new HausBauen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new HausAbbauen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Hypothek($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Infos($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Kaufen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new KaufenJa($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new KaufenNein($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new MieteBezahlen($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new NichtBieten($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Start($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Wuerfeln($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new ZugBeenden($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Zurueck($this), $this);
		
		$this->ereignis = new Ereigniskarte($this);
		$this->gemeinschaft = new Gemeinschaftskarte($this);
		$this->hypothekui = new HypothekUI($this);
		$this->handelnmain = new HandelnMain($this);
		$this->infomain = new InfoMain($this);
		$this->abbauenui = new AbbauenUI($this);
		$this->bauenui = new BauenUI($this);
    }
	
	public function onDisable(): Void{
		$gamecfg = new Config($this->getDataFolder().'game.yml', Config::YAML);
		$players = new Config($this->getDataFolder().'player.yml', Config::YAML);
		$players->set("player1", null);
	    $players->set("player2", null);
	    $players->set("player3", null);
	    $players->set("player4", null);
	    $players->save();
		$gamecfg->set("lastpoints", 0);
		$gamecfg->set("start", false);
		$gamecfg->set("turn", null);
		$gamecfg->set("pasch", 0);
		$gamecfg->set("wurf", false);
		$gamecfg->set("miete", false);
		$gamecfg->set("freiparken", 0);
		$gamecfg->set("gebot", 0);
		$gamecfg->set("lastg", 1);
		$gamecfg->set("laste", 1);
		$gamecfg->set("bieter1", false);
		$gamecfg->set("bieter2", false);
		$gamecfg->set("bieter3", false);
		$gamecfg->set("bieter4", false);
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
		$gamecfg->set("2hypo", false);
		$gamecfg->set("4hypo", false);
		$gamecfg->set("6hypo", false);
		$gamecfg->set("7hypo", false);
		$gamecfg->set("9hypo", false);
		$gamecfg->set("10hypo", false);
		$gamecfg->set("12hypo", false);
		$gamecfg->set("13hypo", false);
		$gamecfg->set("14hypo", false);
		$gamecfg->set("15hypo", false);
		$gamecfg->set("16hypo", false);
		$gamecfg->set("17hypo", false);
		$gamecfg->set("19hypo", false);
		$gamecfg->set("20hypo", false);
		$gamecfg->set("22hypo", false);
		$gamecfg->set("24hypo", false);
		$gamecfg->set("25hypo", false);
		$gamecfg->set("26hypo", false);
		$gamecfg->set("27hypo", false);
		$gamecfg->set("28hypo", false);
		$gamecfg->set("29hypo", false);
		$gamecfg->set("30hypo", false);
		$gamecfg->set("32hypo", false);
		$gamecfg->set("33hypo", false);
		$gamecfg->set("35hypo", false);
		$gamecfg->set("36hypo", false);
		$gamecfg->set("38hypo", false);
		$gamecfg->set("40hypo", false);
		$gamecfg->set("2haus", 0);
		$gamecfg->set("4haus", 0);
		$gamecfg->set("6haus", 0);
		$gamecfg->set("7haus", 0);
		$gamecfg->set("9haus", 0);
		$gamecfg->set("10haus", 0);
		$gamecfg->set("12haus", 0);
		$gamecfg->set("13haus", 0);
		$gamecfg->set("14haus", 0);
		$gamecfg->set("15haus", 0);
		$gamecfg->set("16haus", 0);
		$gamecfg->set("17haus", 0);
		$gamecfg->set("19haus", 0);
		$gamecfg->set("20haus", 0);
		$gamecfg->set("22haus", 0);
		$gamecfg->set("24haus", 0);
		$gamecfg->set("25haus", 0);
		$gamecfg->set("26haus", 0);
		$gamecfg->set("27haus", 0);
		$gamecfg->set("28haus", 0);
		$gamecfg->set("29haus", 0);
		$gamecfg->set("30haus", 0);
		$gamecfg->set("32haus", 0);
		$gamecfg->set("33haus", 0);
		$gamecfg->set("35haus", 0);
		$gamecfg->set("36haus", 0);
		$gamecfg->set("38haus", 0);
		$gamecfg->set("40haus", 0);
		$gamecfg->save();
	}
	
	function getGemeinschaft() {
        return $this->gemeinschaft;
    }
	
	function getEreignis() {
        return $this->ereignis;
    }
	
	function getHypothekUI() {
        return $this->hypothekui;
    }
	
	function getHandelnMain() {
        return $this->handelnmain;
    }
	
	function getInfoMain() {
        return $this->infomain;
    }
	
	function getBauenUI() {
        return $this->bauenui;
    }
	
	function getAbbauenUI() {
        return $this->abbauenui;
    }
	
	public function getZufall1(){
		return mt_rand(1, 6);
	}
	
	public function getZufall2(){
		return mt_rand(1, 6);
	}
	
	public function removeCarts(Player $player){
		$gamecfg = new Config($this->getDataFolder().'game.yml', Config::YAML);
		$config = new Config($this->getDataFolder().'monopoly.yml', Config::YAML);
		if($gamecfg->get("2") == $player->getName()){
			$x1 = $config->getNested("2.x1");
			$z1 = $config->getNested("2.z1");
			$x2 = $config->getNested("2.x2");
			$z2 = $config->getNested("2.z2");
			$x3 = $config->getNested("2.x3");
			$z3 = $config->getNested("2.z3");
			$x4 = $config->getNested("2.x4");
			$z4 = $config->getNested("2.z4");
			$x5 = $config->getNested("2.x5");
			$z5 = $config->getNested("2.z5");
			$x6 = $config->getNested("2.x6");
			$z6 = $config->getNested("2.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("2haus", 0);
			$gamecfg->set("2", null);
			$gamecfg->save();
		}
		if($gamecfg->get("4") == $player->getName()){
			$x1 = $config->getNested("4.x1");
			$z1 = $config->getNested("4.z1");
			$x2 = $config->getNested("4.x2");
			$z2 = $config->getNested("4.z2");
			$x3 = $config->getNested("4.x3");
			$z3 = $config->getNested("4.z3");
			$x4 = $config->getNested("4.x4");
			$z4 = $config->getNested("4.z4");
			$x5 = $config->getNested("4.x5");
			$z5 = $config->getNested("4.z5");
			$x6 = $config->getNested("4.x6");
			$z6 = $config->getNested("4.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("4haus", 0);
			$gamecfg->set("4", null);
			$gamecfg->save();
		}
		if($gamecfg->get("6") == $player->getName()){
			$gamecfg->set("6", null);
			$gamecfg->save();
		}
		if($gamecfg->get("7") == $player->getName()){
			$x1 = $config->getNested("7.x1");
			$z1 = $config->getNested("7.z1");
			$x2 = $config->getNested("7.x2");
			$z2 = $config->getNested("7.z2");
			$x3 = $config->getNested("7.x3");
			$z3 = $config->getNested("7.z3");
			$x4 = $config->getNested("7.x4");
			$z4 = $config->getNested("7.z4");
			$x5 = $config->getNested("7.x5");
			$z5 = $config->getNested("7.z5");
			$x6 = $config->getNested("7.x6");
			$z6 = $config->getNested("7.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("7haus", 0);
			$gamecfg->set("7", null);
			$gamecfg->save();
		}
		if($gamecfg->get("9") == $player->getName()){
			$x1 = $config->getNested("9.x1");
			$z1 = $config->getNested("9.z1");
			$x2 = $config->getNested("9.x2");
			$z2 = $config->getNested("9.z2");
			$x3 = $config->getNested("9.x3");
			$z3 = $config->getNested("9.z3");
			$x4 = $config->getNested("9.x4");
			$z4 = $config->getNested("9.z4");
			$x5 = $config->getNested("9.x5");
			$z5 = $config->getNested("9.z5");
			$x6 = $config->getNested("9.x6");
			$z6 = $config->getNested("9.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("9haus", 0);
			$gamecfg->set("9", null);
			$gamecfg->save();
		}
		if($gamecfg->get("10") == $player->getName()){
			$x1 = $config->getNested("10.x1");
			$z1 = $config->getNested("10.z1");
			$x2 = $config->getNested("10.x2");
			$z2 = $config->getNested("10.z2");
			$x3 = $config->getNested("10.x3");
			$z3 = $config->getNested("10.z3");
			$x4 = $config->getNested("10.x4");
			$z4 = $config->getNested("10.z4");
			$x5 = $config->getNested("10.x5");
			$z5 = $config->getNested("10.z5");
			$x6 = $config->getNested("10.x6");
			$z6 = $config->getNested("10.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("10haus", 0);
			$gamecfg->set("10", null);
			$gamecfg->save();
		}
		if($gamecfg->get("12") == $player->getName()){
			$x1 = $config->getNested("12.x1");
			$z1 = $config->getNested("12.z1");
			$x2 = $config->getNested("12.x2");
			$z2 = $config->getNested("12.z2");
			$x3 = $config->getNested("12.x3");
			$z3 = $config->getNested("12.z3");
			$x4 = $config->getNested("12.x4");
			$z4 = $config->getNested("12.z4");
			$x5 = $config->getNested("12.x5");
			$z5 = $config->getNested("12.z5");
			$x6 = $config->getNested("12.x6");
			$z6 = $config->getNested("12.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("12haus", 0);
			$gamecfg->set("12", null);
			$gamecfg->save();
		}
		if($gamecfg->get("13") == $player->getName()){
			$gamecfg->set("13", null);
			$gamecfg->save();
		}
		if($gamecfg->get("14") == $player->getName()){
			$x1 = $config->getNested("14.x1");
			$z1 = $config->getNested("14.z1");
			$x2 = $config->getNested("14.x2");
			$z2 = $config->getNested("14.z2");
			$x3 = $config->getNested("14.x3");
			$z3 = $config->getNested("14.z3");
			$x4 = $config->getNested("14.x4");
			$z4 = $config->getNested("14.z4");
			$x5 = $config->getNested("14.x5");
			$z5 = $config->getNested("14.z5");
			$x6 = $config->getNested("14.x6");
			$z6 = $config->getNested("14.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("14haus", 0);
			$gamecfg->set("14", null);
			$gamecfg->save();
		}
		if($gamecfg->get("15") == $player->getName()){
			$x1 = $config->getNested("15.x1");
			$z1 = $config->getNested("15.z1");
			$x2 = $config->getNested("15.x2");
			$z2 = $config->getNested("15.z2");
			$x3 = $config->getNested("15.x3");
			$z3 = $config->getNested("15.z3");
			$x4 = $config->getNested("15.x4");
			$z4 = $config->getNested("15.z4");
			$x5 = $config->getNested("15.x5");
			$z5 = $config->getNested("15.z5");
			$x6 = $config->getNested("15.x6");
			$z6 = $config->getNested("15.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("15haus", 0);
			$gamecfg->set("15", null);
			$gamecfg->save();
		}
		if($gamecfg->get("16") == $player->getName()){
			$gamecfg->set("16", null);
			$gamecfg->save();
		}
		if($gamecfg->get("17") == $player->getName()){
			$x1 = $config->getNested("17.x1");
			$z1 = $config->getNested("17.z1");
			$x2 = $config->getNested("17.x2");
			$z2 = $config->getNested("17.z2");
			$x3 = $config->getNested("17.x3");
			$z3 = $config->getNested("17.z3");
			$x4 = $config->getNested("17.x4");
			$z4 = $config->getNested("17.z4");
			$x5 = $config->getNested("17.x5");
			$z5 = $config->getNested("17.z5");
			$x6 = $config->getNested("17.x6");
			$z6 = $config->getNested("17.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("17haus", 0);
			$gamecfg->set("17", null);
			$gamecfg->save();
		}
		if($gamecfg->get("19") == $player->getName()){
			$x1 = $config->getNested("19.x1");
			$z1 = $config->getNested("19.z1");
			$x2 = $config->getNested("19.x2");
			$z2 = $config->getNested("19.z2");
			$x3 = $config->getNested("19.x3");
			$z3 = $config->getNested("19.z3");
			$x4 = $config->getNested("19.x4");
			$z4 = $config->getNested("19.z4");
			$x5 = $config->getNested("19.x5");
			$z5 = $config->getNested("19.z5");
			$x6 = $config->getNested("19.x6");
			$z6 = $config->getNested("19.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("10haus", 0);
			$gamecfg->set("19", null);
			$gamecfg->save();
		}
		if($gamecfg->get("20") == $player->getName()){
			$x1 = $config->getNested("20.x1");
			$z1 = $config->getNested("20.z1");
			$x2 = $config->getNested("20.x2");
			$z2 = $config->getNested("20.z2");
			$x3 = $config->getNested("20.x3");
			$z3 = $config->getNested("20.z3");
			$x4 = $config->getNested("20.x4");
			$z4 = $config->getNested("20.z4");
			$x5 = $config->getNested("20.x5");
			$z5 = $config->getNested("20.z5");
			$x6 = $config->getNested("20.x6");
			$z6 = $config->getNested("20.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("20haus", 0);
			$gamecfg->set("20", null);
			$gamecfg->save();
		}
		if($gamecfg->get("22") == $player->getName()){
			$x1 = $config->getNested("22.x1");
			$z1 = $config->getNested("22.z1");
			$x2 = $config->getNested("22.x2");
			$z2 = $config->getNested("22.z2");
			$x3 = $config->getNested("22.x3");
			$z3 = $config->getNested("22.z3");
			$x4 = $config->getNested("22.x4");
			$z4 = $config->getNested("22.z4");
			$x5 = $config->getNested("22.x5");
			$z5 = $config->getNested("22.z5");
			$x6 = $config->getNested("22.x6");
			$z6 = $config->getNested("22.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("22haus", 0);
			$gamecfg->set("22", null);
			$gamecfg->save();
		}
		if($gamecfg->get("24") == $player->getName()){
			$x1 = $config->getNested("24.x1");
			$z1 = $config->getNested("24.z1");
			$x2 = $config->getNested("24.x2");
			$z2 = $config->getNested("24.z2");
			$x3 = $config->getNested("24.x3");
			$z3 = $config->getNested("24.z3");
			$x4 = $config->getNested("24.x4");
			$z4 = $config->getNested("24.z4");
			$x5 = $config->getNested("24.x5");
			$z5 = $config->getNested("24.z5");
			$x6 = $config->getNested("24.x6");
			$z6 = $config->getNested("24.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("24haus", 0);
			$gamecfg->set("24", null);
			$gamecfg->save();
		}
		if($gamecfg->get("25") == $player->getName()){
			$x1 = $config->getNested("25.x1");
			$z1 = $config->getNested("25.z1");
			$x2 = $config->getNested("25.x2");
			$z2 = $config->getNested("25.z2");
			$x3 = $config->getNested("25.x3");
			$z3 = $config->getNested("25.z3");
			$x4 = $config->getNested("25.x4");
			$z4 = $config->getNested("25.z4");
			$x5 = $config->getNested("25.x5");
			$z5 = $config->getNested("25.z5");
			$x6 = $config->getNested("25.x6");
			$z6 = $config->getNested("25.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("25haus", 0);
			$gamecfg->set("25", null);
			$gamecfg->save();
		}
		if($gamecfg->get("26") == $player->getName()){
			$gamecfg->set("26", null);
			$gamecfg->save();
		}
		if($gamecfg->get("27") == $player->getName()){
			$x1 = $config->getNested("27.x1");
			$z1 = $config->getNested("27.z1");
			$x2 = $config->getNested("27.x2");
			$z2 = $config->getNested("27.z2");
			$x3 = $config->getNested("27.x3");
			$z3 = $config->getNested("27.z3");
			$x4 = $config->getNested("27.x4");
			$z4 = $config->getNested("27.z4");
			$x5 = $config->getNested("27.x5");
			$z5 = $config->getNested("27.z5");
			$x6 = $config->getNested("27.x6");
			$z6 = $config->getNested("27.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("27haus", 0);
			$gamecfg->set("27", null);
			$gamecfg->save();
		}
		if($gamecfg->get("28") == $player->getName()){
			$x1 = $config->getNested("28.x1");
			$z1 = $config->getNested("28.z1");
			$x2 = $config->getNested("28.x2");
			$z2 = $config->getNested("28.z2");
			$x3 = $config->getNested("28.x3");
			$z3 = $config->getNested("28.z3");
			$x4 = $config->getNested("28.x4");
			$z4 = $config->getNested("28.z4");
			$x5 = $config->getNested("28.x5");
			$z5 = $config->getNested("28.z5");
			$x6 = $config->getNested("28.x6");
			$z6 = $config->getNested("28.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("28haus", 0);
			$gamecfg->set("28", null);
			$gamecfg->save();
		}
		if($gamecfg->get("29") == $player->getName()){
			$gamecfg->set("29", null);
			$gamecfg->save();
		}
		if($gamecfg->get("30") == $player->getName()){
			$x1 = $config->getNested("30.x1");
			$z1 = $config->getNested("30.z1");
			$x2 = $config->getNested("30.x2");
			$z2 = $config->getNested("30.z2");
			$x3 = $config->getNested("30.x3");
			$z3 = $config->getNested("30.z3");
			$x4 = $config->getNested("30.x4");
			$z4 = $config->getNested("30.z4");
			$x5 = $config->getNested("30.x5");
			$z5 = $config->getNested("30.z5");
			$x6 = $config->getNested("30.x6");
			$z6 = $config->getNested("30.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("30haus", 0);
			$gamecfg->set("30", null);
			$gamecfg->save();
		}
		if($gamecfg->get("32") == $player->getName()){
			$x1 = $config->getNested("32.x1");
			$z1 = $config->getNested("32.z1");
			$x2 = $config->getNested("32.x2");
			$z2 = $config->getNested("32.z2");
			$x3 = $config->getNested("32.x3");
			$z3 = $config->getNested("32.z3");
			$x4 = $config->getNested("32.x4");
			$z4 = $config->getNested("32.z4");
			$x5 = $config->getNested("32.x5");
			$z5 = $config->getNested("32.z5");
			$x6 = $config->getNested("32.x6");
			$z6 = $config->getNested("32.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("32haus", 0);
			$gamecfg->set("32", null);
			$gamecfg->save();
		}
		if($gamecfg->get("33") == $player->getName()){
			$x1 = $config->getNested("33.x1");
			$z1 = $config->getNested("33.z1");
			$x2 = $config->getNested("33.x2");
			$z2 = $config->getNested("33.z2");
			$x3 = $config->getNested("33.x3");
			$z3 = $config->getNested("33.z3");
			$x4 = $config->getNested("33.x4");
			$z4 = $config->getNested("33.z4");
			$x5 = $config->getNested("33.x5");
			$z5 = $config->getNested("33.z5");
			$x6 = $config->getNested("33.x6");
			$z6 = $config->getNested("33.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("33haus", 0);
			$gamecfg->set("33", null);
			$gamecfg->save();
		}
		if($gamecfg->get("35") == $player->getName()){
			$x1 = $config->getNested("35.x1");
			$z1 = $config->getNested("35.z1");
			$x2 = $config->getNested("35.x2");
			$z2 = $config->getNested("35.z2");
			$x3 = $config->getNested("35.x3");
			$z3 = $config->getNested("35.z3");
			$x4 = $config->getNested("35.x4");
			$z4 = $config->getNested("35.z4");
			$x5 = $config->getNested("35.x5");
			$z5 = $config->getNested("35.z5");
			$x6 = $config->getNested("35.x6");
			$z6 = $config->getNested("35.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("35haus", 0);
			$gamecfg->set("35", null);
			$gamecfg->save();
		}
		if($gamecfg->get("36") == $player->getName()){
			$gamecfg->set("36", null);
			$gamecfg->save();
		}
		if($gamecfg->get("38") == $player->getName()){
			$x1 = $config->getNested("38.x1");
			$z1 = $config->getNested("38.z1");
			$x2 = $config->getNested("38.x2");
			$z2 = $config->getNested("38.z2");
			$x3 = $config->getNested("38.x3");
			$z3 = $config->getNested("38.z3");
			$x4 = $config->getNested("38.x4");
			$z4 = $config->getNested("38.z4");
			$x5 = $config->getNested("38.x5");
			$z5 = $config->getNested("38.z5");
			$x6 = $config->getNested("38.x6");
			$z6 = $config->getNested("38.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("38haus", 0);
			$gamecfg->set("38", null);
			$gamecfg->save();
		}
		if($gamecfg->get("40") == $player->getName()){
			$x1 = $config->getNested("40.x1");
			$z1 = $config->getNested("40.z1");
			$x2 = $config->getNested("40.x2");
			$z2 = $config->getNested("40.z2");
			$x3 = $config->getNested("40.x3");
			$z3 = $config->getNested("40.z3");
			$x4 = $config->getNested("40.x4");
			$z4 = $config->getNested("40.z4");
			$x5 = $config->getNested("40.x5");
			$z5 = $config->getNested("40.z5");
			$x6 = $config->getNested("40.x6");
			$z6 = $config->getNested("40.z6");
			$y = 5;
			$y1 = 6;
			$player->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x5, $y1, $z5), Block::get(0, 0));
			$player->getLevel()->setBlock(new Vector3($x6, $y1, $z6), Block::get(0, 0));
			$gamecfg->set("40haus", 0);
			$gamecfg->set("40", null);
			$gamecfg->save();
		}
	}
	
	public function removeHypo(Player $player){
		$gamecfg = new Config($this->getDataFolder().'game.yml', Config::YAML);
		if($gamecfg->get("2") == $player->getName()){
			$gamecfg->set("2hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("4") == $player->getName()){
			$gamecfg->set("4hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("6") == $player->getName()){
			$gamecfg->set("6hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("7") == $player->getName()){
			$gamecfg->set("7hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("9") == $player->getName()){
			$gamecfg->set("9hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("10") == $player->getName()){
			$gamecfg->set("10hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("12") == $player->getName()){
			$gamecfg->set("12hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("13") == $player->getName()){
			$gamecfg->set("13hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("14") == $player->getName()){
			$gamecfg->set("14hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("15") == $player->getName()){
			$gamecfg->set("15hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("16") == $player->getName()){
			$gamecfg->set("16hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("17") == $player->getName()){
			$gamecfg->set("17hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("19") == $player->getName()){
			$gamecfg->set("19hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("20") == $player->getName()){
			$gamecfg->set("20hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("22") == $player->getName()){
			$gamecfg->set("22hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("24") == $player->getName()){
			$gamecfg->set("24hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("25") == $player->getName()){
			$gamecfg->set("25hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("26") == $player->getName()){
			$gamecfg->set("26hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("27") == $player->getName()){
			$gamecfg->set("27hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("28") == $player->getName()){
			$gamecfg->set("28hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("29") == $player->getName()){
			$gamecfg->set("29hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("30") == $player->getName()){
			$gamecfg->set("30hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("32") == $player->getName()){
			$gamecfg->set("32hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("33") == $player->getName()){
			$gamecfg->set("33hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("35") == $player->getName()){
			$gamecfg->set("35hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("36") == $player->getName()){
			$gamecfg->set("36hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("38") == $player->getName()){
			$gamecfg->set("38hypo", false);
			$gamecfg->save();
		}
		if($gamecfg->get("40") == $player->getName()){
			$gamecfg->set("40hypo", false);
			$gamecfg->save();
		}
	}
	
	public function isFullStreet(Player $player, $feld){
		$gamecfg = new Config($this->getDataFolder().'game.yml', Config::YAML);
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
		if($feld == 13 or $feld == 29){
		    if($gamecfg->get("13") == $player->getName() and $gamecfg->get("29") == $player->getName()){
			    return "yes";
		    }
		}
		return "no";
	}
	
	public function getTrainCount(Player $player, $feld){
		$gamecfg = new Config($this->getDataFolder().'game.yml', Config::YAML);
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