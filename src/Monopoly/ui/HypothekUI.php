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
			$playerMoney = EconomyAPI::getInstance()->myMoney($player);
			if($this->isHypothek($data[1]) === "no"){
				EconomyAPI::getInstance()->addMoney($player, $config->getNested($data[1].".hypo"));
				$gamecfg->set($data[1]."hypo", true);
				$gamecfg->save();
				$player->sendMessage("§bMono§6poly: §aDu hast die Strasse §d".$config->getNested($data[1].".name")."§a mit einer Hypothek von §d ".$config->getNested($data[1].".hypo")."§a$ belastet. Das Geld wurde auf dein Konto überwiesen");
			}else{
				if($playerMoney >= $config->getNested($data[1].".hypo")){
				    EconomyAPI::getInstance()->reduceMoney($player, $config->getNested($data[1].".hypo"));
				    $gamecfg->set($data[1]."hypo", false);
				    $gamecfg->save();
					$player->sendMessage("§bMono§6poly: §aDu hast die Hypothek der Strasse §d".$config->getNested($data[1].".name")."§a von §d".$config->getNested($data[1].".hypo")."§a$ beglichen.");
				}else{
					$player->sendMessage("§bMono§6poly: §cDu hast nicht genug Geld um die Hypothek zu begleichen.");
				}
			}
		});
		$form->setTitle("§bHypothek");
		$form->addLabel("§6Nimm eine Hypothek auf oder bezahle eine ab.\n§6Gib dazu einfach die Strassen Nummer an.\n§6Ist auf der Strasse bereits eine Hypothek bezahlst du sie ab.\n\n§6Deine Strassen sind:\n§f".$this->getPlayerStreetNames($player));
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
	
	public function getPlayerStreetNames(Player $player){
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		if($gamecfg->get("2") == $player->getName()){
			$x = $config->getNested("2.name");
			$a = "2 = ".$x."\n";
		}else{
			$a = "";
		}
		if($gamecfg->get("4") == $player->getName()){
			$x = $config->getNested("4.name");
			$b = "4 = ".$x."\n";
		}else{
			$b = "";
		}
		if($gamecfg->get("6") == $player->getName()){
			$x = $config->getNested("6.name");
			$c = "6 = ".$x."\n";
		}else{
			$c = "";
		}
		if($gamecfg->get("7") == $player->getName()){
			$x = $config->getNested("7.name");
			$d = "7 = ".$x."\n";
		}else{
			$d = "";
		}
		if($gamecfg->get("9") == $player->getName()){
			$x = $config->getNested("9.name");
			$e = "9 = ".$x."\n";
		}else{
			$e = "";
		}
		if($gamecfg->get("10") == $player->getName()){
			$x = $config->getNested("10.name");
			$f = "10 = ".$x."\n";
		}else{
			$f = "";
		}
		if($gamecfg->get("12") == $player->getName()){
			$x = $config->getNested("12.name");
			$g = "12 = ".$x."\n";
		}else{
			$g = "";
		}
		if($gamecfg->get("13") == $player->getName()){
			$x = $config->getNested("13.name");
			$h = "13 = ".$x."\n";
		}else{
			$h = "";
		}
		if($gamecfg->get("14") == $player->getName()){
			$x = $config->getNested("14.name");
			$i = "14 = ".$x."\n";
		}else{
			$i = "";
		}
		if($gamecfg->get("15") == $player->getName()){
			$x = $config->getNested("15.name");
			$j = "15 = ".$x."\n";
		}else{
			$j = "";
		}
		if($gamecfg->get("16") == $player->getName()){
			$x = $config->getNested("16.name");
			$k = "16 = ".$x."\n";
		}else{
			$k = "";
		}
		if($gamecfg->get("17") == $player->getName()){
			$x = $config->getNested("17.name");
			$l = "17 = ".$x."\n";
		}else{
			$l = "";
		}
		if($gamecfg->get("19") == $player->getName()){
			$x = $config->getNested("19.name");
			$m = "19 = ".$x."\n";
		}else{
			$m = "";
		}
		if($gamecfg->get("20") == $player->getName()){
			$x = $config->getNested("20.name");
			$n = "20 = ".$x."\n";
		}else{
			$n = "";
		}
		if($gamecfg->get("22") == $player->getName()){
			$x = $config->getNested("22.name");
			$o = "22 = ".$x."\n";
		}else{
			$o = "";
		}
		if($gamecfg->get("24") == $player->getName()){
			$x = $config->getNested("24.name");
			$p = "24 = ".$x."\n";
		}else{
			$p = "";
		}
		if($gamecfg->get("25") == $player->getName()){
			$x = $config->getNested("25.name");
			$q = "25 = ".$x."\n";
		}else{
			$q = "";
		}
		if($gamecfg->get("26") == $player->getName()){
			$x = $config->getNested("26.name");
			$v = "26 = ".$x."\n";
		}else{
			$v = "";
		}
		if($gamecfg->get("27") == $player->getName()){
			$x = $config->getNested("27.name");
			$w = "27 = ".$x."\n";
		}else{
			$w = "";
		}
		if($gamecfg->get("28") == $player->getName()){
			$x = $config->getNested("28.name");
			$x1 = "28 = ".$x."\n";
		}else{
			$x1 = "";
		}
		if($gamecfg->get("29") == $player->getName()){
			$x = $config->getNested("29.name");
			$y = "29 = ".$x."\n";
		}else{
			$y = "";
		}
		if($gamecfg->get("30") == $player->getName()){
			$x = $config->getNested("30.name");
			$z = "30 = ".$x."\n";
		}else{
			$z = "";
		}
		if($gamecfg->get("32") == $player->getName()){
			$x = $config->getNested("32.name");
			$a1 = "32 = ".$x."\n";
		}else{
			$a1 = "";
		}
		if($gamecfg->get("33") == $player->getName()){
			$x = $config->getNested("33.name");
			$b1 = "33 = ".$x."\n";
		}else{
			$b1 = "";
		}
		if($gamecfg->get("35") == $player->getName()){
			$x = $config->getNested("35.name");
			$c1 = "35 = ".$x."\n";
		}else{
			$c1 = "";
		}
		if($gamecfg->get("36") == $player->getName()){
			$x = $config->getNested("36.name");
			$d1 = "36 = ".$x."\n";
		}else{
			$d1 = "";
		}
		if($gamecfg->get("38") == $player->getName()){
			$x = $config->getNested("38.name");
			$e1 = "38 = ".$x."\n";
		}else{
			$e1 = "";
		}
		if($gamecfg->get("40") == $player->getName()){
			$x = $config->getNested("40.name");
			$f1 = "40 = ".$x."\n";
		}else{
			$f1 = "";
		}
		$msg = $a.$b.$c.$d.$e.$f.$g.$h.$i.$j.$k.$l.$m.$n.$o.$p.$q.$v.$w.$x1.$y.$z.$a1.$b1.$c1.$d1.$e1.$f1;
		return $msg;
	}
}