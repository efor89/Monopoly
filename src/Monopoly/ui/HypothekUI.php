<?php

namespace Monopoly\ui;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use Monopoly\Main;
use Monopoly\aktionen\Wuerfeln;
use onebone\economyapi\EconomyAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

class HypothekUI{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function HypothekUI($player){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function (Player $player, array $data = null) {
			$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		    $gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
			$result = $data;
			if ($result === null) {
				return true;
			}
			if(empty($data[1])){
				return true;
			}
			if (!is_numeric($data[1])){
                $player->sendMessage("§bMono§6poly: §cGib eine gültige Zahl an.");
                return true;
            }
			if($this->isPlayerStreet($player, $data[1]) === "no"){
				$player->sendMessage("§bMono§6poly: §cDie Strasse gehört dir nicht oder sie existiert nicht.");
				return true;
			}
			if($gamecfg->get($data[1]."haus") > 0){
				$player->sendMessage("§bMono§6poly: §cDu kannst für die Strasse keine Hypothek auf nehmen da mindestens 1 Haus auf der Strasse steht.");
				return true;
			}
			$playerMoney = EconomyAPI::getInstance()->myMoney($player);
			$players = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
		    $Player1 = $players->get("player1");
		    $Player2 = $players->get("player2");
		    $Player3 = $players->get("player3");
		    $Player4 = $players->get("player4");
			if($this->isHypothek($data[1]) === "no"){
				EconomyAPI::getInstance()->addMoney($player, $config->getNested($data[1].".hypo"));
				$gamecfg->set($data[1]."hypo", true);
				$gamecfg->save();
				$player->sendMessage("§bMono§6poly: §aDu hast die Strasse §d".$config->getNested($data[1].".name")."§a mit einer Hypothek von §d ".$config->getNested($data[1].".hypo")."§a$ belastet. Das Geld wurde auf dein Konto überwiesen");
				$block = Block::get(236, 14);
			    $x = $config->getNested($data[1].".bx1");
			    $z = $config->getNested($data[1].".bz1");
				$y = 9;
		        $player->getLevel()->setBlock(new Vector3($x, $y, $z), $block);
			}else{
				if($playerMoney >= $config->getNested($data[1].".hypo")){
				    EconomyAPI::getInstance()->reduceMoney($player, $config->getNested($data[1].".hypo"));
				    $gamecfg->set($data[1]."hypo", false);
				    $gamecfg->save();
					$player->sendMessage("§bMono§6poly: §aDu hast die Hypothek der Strasse §d".$config->getNested($data[1].".name")."§a von §d".$config->getNested($data[1].".hypo")."§a$ beglichen.");
					if($Player1 == $player->getName()){
					    $block = Block::get(165, 0);
					}elseif($Player2 == $player->getName()){
					    $block = Block::get(19, 0);
					}elseif($Player3 == $player->getName()){
					    $block = Block::get(91, 0);
					}elseif($Player4 == $player->getName()){
					    $block = Block::get(170, 0);
					}
			        $x = $config->getNested($data[1].".bx1");
			        $z = $config->getNested($data[1].".bz1");
					$y = 9;
			        $player->getLevel()->setBlock(new Vector3($x, $y, $z), $block);
				}else{
					$player->sendMessage("§bMono§6poly: §cDu hast nicht genug Geld um die Hypothek zu begleichen.");
				}
			}
		});
		$form->setTitle("§bHypothek");
		$form->addLabel("§6Nimm eine Hypothek auf oder bezahle eine ab.\n§6Gib dazu einfach die Strassen Nummer an.\n§6Ist auf der Strasse bereits eine Hypothek bezahlst du sie ab.\n\n§6Deine Strassen sind:\n§f".$this->getPlayerStreetNames($player)."\n\n§6Strassen auf den eine Hypothek ist:\n§f".$this->getHypoStreets());
        $form->addInput("§rGib eine Zahl an", "zb. 2");				
		$form->sendToPlayer($player);
		return true;
	}
	
	public function isHypothek($data){
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		if($gamecfg->get($data."hypo") === true){
			return "yes";
		}
		return "no";
	}
	
	public function isPlayerStreet(Player $player, $data){
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		if($gamecfg->get($data) == $player->getName()){
			return "yes";
		}
		return "no";
	}
	
	public function getHypoStreets(){
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		if($gamecfg->get("2hypo") == true){
			$x = $config->getNested("2.name");
			$a = "2 = §a".$x."§r\n";
		}else{
			$a = "";
		}
		if($gamecfg->get("4hypo") == true){
			$x = $config->getNested("4.name");
			$b = "4 = §a".$x."§r\n";
		}else{
			$b = "";
		}
		if($gamecfg->get("6hypo") == true){
			$x = $config->getNested("6.name");
			$c = "6 = §7".$x."§r\n";
		}else{
			$c = "";
		}
		if($gamecfg->get("7hypo") == true){
			$x = $config->getNested("7.name");
			$d = "7 = §b".$x."§r\n";
		}else{
			$d = "";
		}
		if($gamecfg->get("9hypo") == true){
			$x = $config->getNested("9.name");
			$e = "9 = §b".$x."§r\n";
		}else{
			$e = "";
		}
		if($gamecfg->get("10hypo") == true){
			$x = $config->getNested("10.name");
			$f = "10 = §b".$x."§r\n";
		}else{
			$f = "";
		}
		if($gamecfg->get("12hypo") == true){
			$x = $config->getNested("12.name");
			$g = "12 = §d".$x."§r\n";
		}else{
			$g = "";
		}
		if($gamecfg->get("13hypo") == true){
			$x = $config->getNested("13.name");
			$h = "13 = §f".$x."§r\n";
		}else{
			$h = "";
		}
		if($gamecfg->get("14hypo") == true){
			$x = $config->getNested("14.name");
			$i = "14 = §d".$x."§r\n";
		}else{
			$i = "";
		}
		if($gamecfg->get("15hypo") == true){
			$x = $config->getNested("15.name");
			$j = "15 = §d".$x."§r\n";
		}else{
			$j = "";
		}
		if($gamecfg->get("16hypo") == true){
			$x = $config->getNested("16.name");
			$k = "16 = §7".$x."§r\n";
		}else{
			$k = "";
		}
		if($gamecfg->get("17hypo") == true){
			$x = $config->getNested("17.name");
			$l = "17 = §6".$x."§r\n";
		}else{
			$l = "";
		}
		if($gamecfg->get("19hypo") == true){
			$x = $config->getNested("19.name");
			$m = "19 = §6".$x."§r\n";
		}else{
			$m = "";
		}
		if($gamecfg->get("20hypo") == true){
			$x = $config->getNested("20.name");
			$n = "20 = §6".$x."§r\n";
		}else{
			$n = "";
		}
		if($gamecfg->get("22hypo") == true){
			$x = $config->getNested("22.name");
			$o = "22 = §4".$x."§r\n";
		}else{
			$o = "";
		}
		if($gamecfg->get("24hypo") == true){
			$x = $config->getNested("24.name");
			$p = "24 = §4".$x."§r\n";
		}else{
			$p = "";
		}
		if($gamecfg->get("25hypo") == true){
			$x = $config->getNested("25.name");
			$q = "25 = §4".$x."§r\n";
		}else{
			$q = "";
		}
		if($gamecfg->get("26hypo") == true){
			$x = $config->getNested("26.name");
			$v = "26 = §7".$x."§r\n";
		}else{
			$v = "";
		}
		if($gamecfg->get("27hypo") == true){
			$x = $config->getNested("27.name");
			$w = "27 = §e".$x."§r\n";
		}else{
			$w = "";
		}
		if($gamecfg->get("28hypo") == true){
			$x = $config->getNested("28.name");
			$x1 = "28 = §e".$x."§r\n";
		}else{
			$x1 = "";
		}
		if($gamecfg->get("29hypo") == true){
			$x = $config->getNested("29.name");
			$y = "29 = §f".$x."§r\n";
		}else{
			$y = "";
		}
		if($gamecfg->get("30hypo") == true){
			$x = $config->getNested("30.name");
			$z = "30 = §e".$x."§r\n";
		}else{
			$z = "";
		}
		if($gamecfg->get("32hypo") == true){
			$x = $config->getNested("32.name");
			$a1 = "32 = §2".$x."§r\n";
		}else{
			$a1 = "";
		}
		if($gamecfg->get("33hypo") == true){
			$x = $config->getNested("33.name");
			$b1 = "33 = §2".$x."§r\n";
		}else{
			$b1 = "";
		}
		if($gamecfg->get("35hypo") == true){
			$x = $config->getNested("35.name");
			$c1 = "35 = §2".$x."§r\n";
		}else{
			$c1 = "";
		}
		if($gamecfg->get("36hypo") == true){
			$x = $config->getNested("36.name");
			$d1 = "36 = §7".$x."§r\n";
		}else{
			$d1 = "";
		}
		if($gamecfg->get("38hypo") == true){
			$x = $config->getNested("38.name");
			$e1 = "38 = §1".$x."§r\n";
		}else{
			$e1 = "";
		}
		if($gamecfg->get("40hypo") == true){
			$x = $config->getNested("40.name");
			$f1 = "40 = §1".$x."§r\n";
		}else{
			$f1 = "";
		}
		$msg = $a.$b.$c.$d.$e.$f.$g.$h.$i.$j.$k.$l.$m.$n.$o.$p.$q.$v.$w.$x1.$y.$z.$a1.$b1.$c1.$d1.$e1.$f1;
		return $msg;
	}
	
	public function getPlayerStreetNames(Player $player){
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		if($gamecfg->get("2") == $player->getName()){
			$x = $config->getNested("2.name");
			$a = "2 = §a".$x."§r\n";
		}else{
			$a = "";
		}
		if($gamecfg->get("4") == $player->getName()){
			$x = $config->getNested("4.name");
			$b = "4 = §a".$x."§r\n";
		}else{
			$b = "";
		}
		if($gamecfg->get("6") == $player->getName()){
			$x = $config->getNested("6.name");
			$c = "6 = §7".$x."§r\n";
		}else{
			$c = "";
		}
		if($gamecfg->get("7") == $player->getName()){
			$x = $config->getNested("7.name");
			$d = "7 = §b".$x."§r\n";
		}else{
			$d = "";
		}
		if($gamecfg->get("9") == $player->getName()){
			$x = $config->getNested("9.name");
			$e = "9 = §b".$x."§r\n";
		}else{
			$e = "";
		}
		if($gamecfg->get("10") == $player->getName()){
			$x = $config->getNested("10.name");
			$f = "10 = §b".$x."§r\n";
		}else{
			$f = "";
		}
		if($gamecfg->get("12") == $player->getName()){
			$x = $config->getNested("12.name");
			$g = "12 = §d".$x."§r\n";
		}else{
			$g = "";
		}
		if($gamecfg->get("13") == $player->getName()){
			$x = $config->getNested("13.name");
			$h = "13 = §f".$x."§r\n";
		}else{
			$h = "";
		}
		if($gamecfg->get("14") == $player->getName()){
			$x = $config->getNested("14.name");
			$i = "14 = §d".$x."§r\n";
		}else{
			$i = "";
		}
		if($gamecfg->get("15") == $player->getName()){
			$x = $config->getNested("15.name");
			$j = "15 = §d".$x."§r\n";
		}else{
			$j = "";
		}
		if($gamecfg->get("16") == $player->getName()){
			$x = $config->getNested("16.name");
			$k = "16 = §7".$x."§r\n";
		}else{
			$k = "";
		}
		if($gamecfg->get("17") == $player->getName()){
			$x = $config->getNested("17.name");
			$l = "17 = §6".$x."§r\n";
		}else{
			$l = "";
		}
		if($gamecfg->get("19") == $player->getName()){
			$x = $config->getNested("19.name");
			$m = "19 = §6".$x."§r\n";
		}else{
			$m = "";
		}
		if($gamecfg->get("20") == $player->getName()){
			$x = $config->getNested("20.name");
			$n = "20 = §6".$x."§r\n";
		}else{
			$n = "";
		}
		if($gamecfg->get("22") == $player->getName()){
			$x = $config->getNested("22.name");
			$o = "22 = §4".$x."§r\n";
		}else{
			$o = "";
		}
		if($gamecfg->get("24") == $player->getName()){
			$x = $config->getNested("24.name");
			$p = "24 = §4".$x."§r\n";
		}else{
			$p = "";
		}
		if($gamecfg->get("25") == $player->getName()){
			$x = $config->getNested("25.name");
			$q = "25 = §4".$x."§r\n";
		}else{
			$q = "";
		}
		if($gamecfg->get("26") == $player->getName()){
			$x = $config->getNested("26.name");
			$v = "26 = §7".$x."§r\n";
		}else{
			$v = "";
		}
		if($gamecfg->get("27") == $player->getName()){
			$x = $config->getNested("27.name");
			$w = "27 = §e".$x."§r\n";
		}else{
			$w = "";
		}
		if($gamecfg->get("28") == $player->getName()){
			$x = $config->getNested("28.name");
			$x1 = "28 = §e".$x."§r\n";
		}else{
			$x1 = "";
		}
		if($gamecfg->get("29") == $player->getName()){
			$x = $config->getNested("29.name");
			$y = "29 = §f".$x."§r\n";
		}else{
			$y = "";
		}
		if($gamecfg->get("30") == $player->getName()){
			$x = $config->getNested("30.name");
			$z = "30 = §e".$x."§r\n";
		}else{
			$z = "";
		}
		if($gamecfg->get("32") == $player->getName()){
			$x = $config->getNested("32.name");
			$a1 = "32 = §2".$x."§r\n";
		}else{
			$a1 = "";
		}
		if($gamecfg->get("33") == $player->getName()){
			$x = $config->getNested("33.name");
			$b1 = "33 = §2".$x."§r\n";
		}else{
			$b1 = "";
		}
		if($gamecfg->get("35") == $player->getName()){
			$x = $config->getNested("35.name");
			$c1 = "35 = §2".$x."§r\n";
		}else{
			$c1 = "";
		}
		if($gamecfg->get("36") == $player->getName()){
			$x = $config->getNested("36.name");
			$d1 = "36 = §7".$x."§r\n";
		}else{
			$d1 = "";
		}
		if($gamecfg->get("38") == $player->getName()){
			$x = $config->getNested("38.name");
			$e1 = "38 = §1".$x."§r\n";
		}else{
			$e1 = "";
		}
		if($gamecfg->get("40") == $player->getName()){
			$x = $config->getNested("40.name");
			$f1 = "40 = §1".$x."§r\n";
		}else{
			$f1 = "";
		}
		$msg = $a.$b.$c.$d.$e.$f.$g.$h.$i.$j.$k.$l.$m.$n.$o.$p.$q.$v.$w.$x1.$y.$z.$a1.$b1.$c1.$d1.$e1.$f1;
		return $msg;
	}
}