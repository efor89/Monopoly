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
				    EconomyAPI::getInstance()->addMoney($player, $config->getNested($data[1].".hypo"));
				    $gamecfg->set($data[1]."hypo", true);
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
			$a = $config->getNested("2.name");
		}else{
			$a = "";
		}
		if($gamecfg->get("4") == $player->getName()){
			$b = $config->getNested("4.name");
		}else{
			$b = "";
		}
		if($gamecfg->get("6") == $player->getName()){
			$c = $config->getNested("6.name");
		}else{
			$c = "";
		}
		if($gamecfg->get("7") == $player->getName()){
			$d = $config->getNested("7.name");
		}else{
			$d = "";
		}
		if($gamecfg->get("9") == $player->getName()){
			$e = $config->getNested("9.name");
		}else{
			$e = "";
		}
		if($gamecfg->get("10") == $player->getName()){
			$f = $config->getNested("10.name");
		}else{
			$f = "";
		}
		if($gamecfg->get("12") == $player->getName()){
			$g = $config->getNested("12.name");
		}else{
			$g = "";
		}
		if($gamecfg->get("13") == $player->getName()){
			$h = $config->getNested("13.name");
		}else{
			$h = "";
		}
		if($gamecfg->get("14") == $player->getName()){
			$i = $config->getNested("14.name");
		}else{
			$i = "";
		}
		if($gamecfg->get("15") == $player->getName()){
			$j = $config->getNested("15.name");
		}else{
			$j = "";
		}
		if($gamecfg->get("16") == $player->getName()){
			$k = $config->getNested("16.name");
		}else{
			$k = "";
		}
		if($gamecfg->get("17") == $player->getName()){
			$l = $config->getNested("17.name");
		}else{
			$l = "";
		}
		if($gamecfg->get("19") == $player->getName()){
			$m = $config->getNested("19.name");
		}else{
			$m = "";
		}
		if($gamecfg->get("20") == $player->getName()){
			$n = $config->getNested("20.name");
		}else{
			$n = "";
		}
		if($gamecfg->get("22") == $player->getName()){
			$o = $config->getNested("22.name");
		}else{
			$o = "";
		}
		if($gamecfg->get("24") == $player->getName()){
			$p = $config->getNested("24.name");
		}else{
			$p = "";
		}
		if($gamecfg->get("25") == $player->getName()){
			$q = $config->getNested("25.name");
		}else{
			$q = "";
		}
		if($gamecfg->get("26") == $player->getName()){
			$v = $config->getNested("26.name");
		}else{
			$v = "";
		}
		if($gamecfg->get("27") == $player->getName()){
			$w = $config->getNested("27.name");
		}else{
			$w = "";
		}
		if($gamecfg->get("28") == $player->getName()){
			$x = $config->getNested("28.name");
		}else{
			$x = "";
		}
		if($gamecfg->get("29") == $player->getName()){
			$y = $config->getNested("29.name");
		}else{
			$y = "";
		}
		if($gamecfg->get("30") == $player->getName()){
			$z = $config->getNested("30.name");
		}else{
			$z = "";
		}
		if($gamecfg->get("32") == $player->getName()){
			$a1 = $config->getNested("32.name");
		}else{
			$a1 = "";
		}
		if($gamecfg->get("33") == $player->getName()){
			$b1 = $config->getNested("33.name");
		}else{
			$b1 = "";
		}
		if($gamecfg->get("35") == $player->getName()){
			$c1 = $config->getNested("35.name");
		}else{
			$c1 = "";
		}
		if($gamecfg->get("36") == $player->getName()){
			$d1 = $config->getNested("36.name");
		}else{
			$d1 = "";
		}
		if($gamecfg->get("38") == $player->getName()){
			$e1 = $config->getNested("38.name");
		}else{
			$e1 = "";
		}
		if($gamecfg->get("40") == $player->getName()){
			$f1 = $config->getNested("40.name");
		}else{
			$f1 = "";
		}
		$msg = "2 = ".$a."\n4 = ".$b."\n6 = ".$c."\n7 = ".$d."\n9 = ".$e."\n10 = ".$f."\n12 = ".$g."\n13 = ".$h."\n14 = ".$i."\n15 = ".$j."\n16 = ".$k."\n17 = ".$l."\n19 = ".$m."\n20 = ".$n."\n22 = ".$o."\n24 = ".$p."\n25 = ".$q."\n26 = ".$v."\n27 = ".$w."\n28 = ".$x."\n29 = ".$y."\n30 = ".$z."\n31 = ".$a1."\n33 = ".$b1."\n35 = ".$c1."\n36 = ".$d1."\n38 = ".$e1."\n40 = ".$f1;
		return $msg;
	}
}