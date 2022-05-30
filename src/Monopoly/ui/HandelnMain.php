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

class HandelnMain{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function HandelnMain(Player $player){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function (Player $player, array $data = null) {
			$result = $data;
			if ($result === null) {
				return true;
			}
			if(empty($data[1])){
				return true;
			}
			$cfg = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
			if($player->getName() == $data[1]){
				$player->sendMessage("§bMono§6poly: §cDu kannst nicht mit dir selber Handeln.");
				return true;
			}
			if($cfg->get("player1") == $data[1] or $cfg->get("player2") == $data[1] or $cfg->get("player3") == $data[1] or $cfg->get("player4") == $data[1]){
				$target = Server::getInstance()->getPlayer($data[1]);
			    $this->Handeln($player, $target);
			}else{
				$player->sendMessage("§bMono§6poly: §cDer Spieler existiert nicht, der Name ist Falsch geschrieben oder der Spieler ist nicht zum Spiel angemeldet.");
			}
		});
		$cfg = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
		$form->setTitle("§bHandeln Hauptmenü");
		$form->addLabel("§6Gib hier den Spieler an mit dem du Handeln möchtest!");
   		$form->addInput("§rGib einen Namen ein\n".$cfg->get("player1")."\n".$cfg->get("player2")."\n".$cfg->get("player3")."\n".$cfg->get("player4")."\n", "Spieler Name");
		$form->sendToPlayer($player);
		return true;
	}
	
	public function Handeln(Player $player, Player $target){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$t_name = $target->getName();
		$form = $api->createCustomForm(function (Player $player, $data) use ($t_name){
			if (($target = Server::getInstance()->getPlayer($t_name)) === null) return;
			$result = $data;
			if ($result === null) {
				return true;
			}
			if(empty($data[1]) and empty($data[2]) and empty($data[3]) and empty($data[4])and empty($data[5]) and empty($data[6])){
				return true;
			}
			if(empty($data[1]) and empty($data[2]) and empty($data[3])){
				$player->sendMessage("§bMono§6poly: §cDu musst entweder Geld oder eine Strasse angeben die du möchtest!");
				return true;
			}
			if(empty($data[4]) and empty($data[5]) and empty($data[6])){
				$player->sendMessage("§bMono§6poly: §cDu musst entweder Geld oder eine Strasse angeben die du anbietest!");
				return true;
			}
			if(empty($data[1]) and empty($data[2]) and empty($data[4])and empty($data[5])){
				$player->sendMessage("§bMono§6poly: §cDu kannst nicht Geld gegen Geld Tauschen.");
				return true;
			}
			if ((!is_numeric($data[1]) and !empty($data[1])) or (!is_numeric($data[2]) and !empty($data[2])) or (!is_numeric($data[3]) and !empty($data[3])) or (!is_numeric($data[4]) and !empty($data[4])) or (!is_numeric($data[5]) and !empty($data[5])) or (!is_numeric($data[6]) and !empty($data[6]))){
                $player->sendMessage("§bMono§6poly: §cGib eine gültige Zahl an.");
                return true;
            }
			if($this->isPlayerStreet($target, $data[1]) === "no"){
				$player->sendMessage("§bMono§6poly: §cDie Strasse gehört dem Spieler nicht oder sie existiert nicht.");
				return true;
			}
			if($this->isPlayerStreet($player, $data[3]) === "no"){
				$player->sendMessage("§bMono§6poly: §cDie Strasse gehört dir nicht oder sie existiert nicht.");
				return true;
			}
			$playerMoney = EconomyAPI::getInstance()->myMoney($player);
			$targetMoney = EconomyAPI::getInstance()->myMoney($target);
			if($playerMoney < $data[4]){
				$player->sendMessage("§bMono§6poly: §cDu hast nicht genug Geld.");
				return true;
			}
			if($targetMoney < $data[2]){
				$player->sendMessage("§bMono§6poly: §cDer Spieler hat nicht genug Geld.");
				return true;
			}
			if(empty($data[1])){
				$street1 = null;
			}else{
			    $street1 = $data[1];
			}
			if(empty($data[2])){
				$street2 = null;
			}else{
			    $street2 = $data[2];
			}
			if(empty($data[4])){
				$street3 = null;
			}else{
			    $street3 = $data[4];
			}
			if(empty($data[5])){
				$street4 = null;
			}else{
			    $street4 = $data[5];
			}
			if(empty($data[3])){
				$money1 = null;
			}else{
			    $money1 = $data[3];
			}
			if(empty($data[6])){
				$money2 = null;
			}else{
			    $money2 = $data[6];
			}
			$this->HandelAccapt($target, $player, $street1, $street2, $street3, $street4, $money1, $money2);
		});
		$playerMoney = EconomyAPI::getInstance()->myMoney($player);
		$targetMoney = EconomyAPI::getInstance()->myMoney($target);
		$form->setTitle("§bHandeln Hauptmenü");
		$form->addLabel("§6Was du vom Spieler möchtest:");
		$form->addLabel("§6Sein Geld: §f".$targetMoney."$");
		$form->addLabel("§6Seine Strassen: \n§f".$this->getPlayerStreetNames($target));
   		$form->addInput("§fStrassennummer 1:", "zb. 2");
		$form->addInput("§fStrassennummer 2:", "zb. 2");
   		$form->addInput("§fGeld:", "zb. 1000");
		$form->addLabel("§6Was du dem Spieler bietest:");
		$form->addLabel("§6Dein Geld: §f".$targetMoney."$");
		$form->addLabel("§6Deine Strassen: \n§f".$this->getPlayerStreetNames($player));
   		$form->addInput("§fStrassennummer 1:", "zb. 4");
		$form->addInput("§fStrassennummer 2:", "zb. 4");
   		$form->addInput("§fGeld:", "zb. 1000");
		$form->sendToPlayer($player);
		return true;
	}
	
	public function HandelAccapt($player, $target, $street1, $street2, $street3, $street4, $money1, $money2){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$t_name = $target->getName();
		$form = $api->createSimpleForm(function (Player $player, int $data = null) use ($t_name, $street1, $street2, $street3, $street4, $money1, $money2){
			if (($target = Server::getInstance()->getPlayer($t_name)) === null) return;
			$result = $data;
			if ($result === null) {
				$target->sendMessage("§bMono§6poly: §cDeine Handelsanfrage wurde abgelehnt!");
				$player->sendMessage("§bMono§6poly: §cDu hast die Handelsanfrage abgelehnt!");
				return true;
			}
			switch ($result) {
				case 0:
				    $gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
				    $playerMoney = EconomyAPI::getInstance()->myMoney($player);
			        $targetMoney = EconomyAPI::getInstance()->myMoney($target);
					if($street1 !== null){
						$gamecfg->set($street1, $target->getName());
						$gamecfg->save();
					}
					if($street2 !== null){
						$gamecfg->set($street2, $target->getName());
						$gamecfg->save();
					}
					if($money1 !== null){
						EconomyAPI::getInstance()->reduceMoney($player, $money1);
						EconomyAPI::getInstance()->addMoney($target, $money1);
					}
					if($street3 !== null){
						$gamecfg->set($street3, $player->getName());
						$gamecfg->save();
					}
					if($street4 !== null){
						$gamecfg->set($street4, $player->getName());
						$gamecfg->save();
					}
					if($money2 !== null){
						EconomyAPI::getInstance()->reduceMoney($target, $money2);
						EconomyAPI::getInstance()->addMoney($player, $money2);
					}
					$target->sendMessage("§bMono§6poly: §aDeine Handelsanfrage wurde akzeptiert!");
					$player->sendMessage("§bMono§6poly: §aDu hast die Handelsanfrage akzeptiert!");
				break;
			}
			switch ($result) {
				case 1:
				    $target->sendMessage("§bMono§6poly: §cDeine Handelsanfrage wurde abgelehnt!");
					$player->sendMessage("§bMono§6poly: §cDu hast die Handelsanfrage abgelehnt!");
				break;
			}
		});
		$form->setTitle("§bHandel Anfrage");
		$form->setContent("§6Das ist eine Handel Anfrage von §d".$target->getName().". Entscheide ob du sie akzeptierst oder ablehnst.");
        $form->addButton("§aAkzeptieren");
		$form->addButton("§dAblehnen");						
		$form->addButton("§cSchließen");
		$form->sendToPlayer($player);
		return true;
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