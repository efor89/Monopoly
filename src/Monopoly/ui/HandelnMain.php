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
			if(empty($data[3]) and empty($data[4]) and empty($data[5]) and empty($data[9])and empty($data[10]) and empty($data[11])){
				return true;
			}
			if(empty($data[3]) and empty($data[4]) and empty($data[5])){
				$player->sendMessage("§bMono§6poly: §cDu musst entweder Geld oder eine Strasse angeben die du möchtest!");
				return true;
			}
			if(empty($data[9]) and empty($data[10]) and empty($data[11])){
				$player->sendMessage("§bMono§6poly: §cDu musst entweder Geld oder eine Strasse angeben die du anbietest!");
				return true;
			}
			if(empty($data[3]) and empty($data[4]) and empty($data[9])and empty($data[10])){
				$player->sendMessage("§bMono§6poly: §cDu kannst nicht Geld gegen Geld Tauschen.");
				return true;
			}
			if ((!is_numeric($data[3]) and !empty($data[3])) or (!is_numeric($data[4]) and !empty($data[4])) or (!is_numeric($data[5]) and !empty($data[5])) or (!is_numeric($data[9]) and !empty($data[9])) or (!is_numeric($data[10]) and !empty($data[10])) or (!is_numeric($data[11]) and !empty($data[11]))){
                $player->sendMessage("§bMono§6poly: §cGib eine gültige Zahl an.");
                return true;
            }
			if($this->isPlayerStreet($target, $data[3]) == "no" and !empty($data[3])){
				$player->sendMessage("§bMono§6poly: §cDie Strasse ".$data[3]." gehört dem Spieler §d".$target->getName()." §cnicht oder sie existiert nicht.");
				return true;
			}
			if($this->isPlayerStreet($target, $data[4]) == "no" and !empty($data[4])){
				$player->sendMessage("§bMono§6poly: §cDie Strasse ".$data[4]." gehört dem Spieler §d".$target->getName()." §cnicht oder sie existiert nicht.");
				return true;
			}
			if($this->isPlayerStreet($player, $data[9]) == "no" and !empty($data[9])){
				$player->sendMessage("§bMono§6poly: §cDie Strasse ".$data[9]." gehört dir nicht oder sie existiert nicht.");
				return true;
			}
			if($this->isPlayerStreet($player, $data[10]) == "no" and !empty($data[10])){
				$player->sendMessage("§bMono§6poly: §cDie Strasse ".$data[10]." gehört dir nicht oder sie existiert nicht.");
				return true;
			}
			$playerMoney = EconomyAPI::getInstance()->myMoney($player);
			$targetMoney = EconomyAPI::getInstance()->myMoney($target);
			if($playerMoney < $data[11]){
				$player->sendMessage("§bMono§6poly: §cDu hast nicht genug Geld.");
				return true;
			}
			if($targetMoney < $data[5]){
				$player->sendMessage("§bMono§6poly: §cDer Spieler hat nicht genug Geld.");
				return true;
			}
			$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
			$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
			if($gamecfg->get($data[3]."haus") > 0){
				$player->sendMessage("§bMono§6poly: §cDu kannst mit der Strasse §d".$config->getNested($data[3].".name")." §cnicht Handeln da mindestens 1 Haus auf der Strasse steht.");
				return true;
			}
			if($gamecfg->get($data[4]."haus") > 0){
				$player->sendMessage("§bMono§6poly: §cDu kannst mit der Strasse §d".$config->getNested($data[4].".name")." §cnicht Handeln da mindestens 1 Haus auf der Strasse steht.");
				return true;
			}
			if($gamecfg->get($data[9]."haus") > 0){
				$player->sendMessage("§bMono§6poly: §cDu kannst mit der Strasse §d".$config->getNested($data[9].".name")." §cnicht Handeln da mindestens 1 Haus auf der Strasse steht.");
				return true;
			}
			if($gamecfg->get($data[10]."haus") > 0){
				$player->sendMessage("§bMono§6poly: §cDu kannst mit der Strasse §d".$config->getNested($data[10].".name")." §cnicht Handeln da mindestens 1 Haus auf der Strasse steht.");
				return true;
			}
			if(empty($data[3])){
				$street1 = null;
			}else{
			    $street1 = $data[3];
			}
			if(empty($data[4])){
				$street2 = null;
			}else{
			    $street2 = $data[4];
			}
			if(empty($data[9])){
				$street3 = null;
			}else{
			    $street3 = $data[9];
			}
			if(empty($data[10])){
				$street4 = null;
			}else{
			    $street4 = $data[10];
			}
			if(empty($data[5])){
				$money1 = null;
			}else{
			    $money1 = $data[5];
			}
			if(empty($data[11])){
				$money2 = null;
			}else{
			    $money2 = $data[11];
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
		$form->addLabel("§6Dein Geld: §f".$playerMoney."$");
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
		$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		if($street1 != null){
			$str = $config->getNested($street1.".name");
			$a = $str."\n";
		}else{
			$a = "";
		}
		if($street2 != null){
			$str = $config->getNested($street2.".name");
			$b = $str."\n";
		}else{
			$b = "";
		}
		if($street3 != null){
			$str = $config->getNested($street3.".name");
			$d = $str."\n";
		}else{
			$d = "";
		}
		if($street1 != null){
			$str = $config->getNested($street4.".name");
			$e = $str."\n";
		}else{
			$e = "";
		}
		if($money1 != null){
			$c = $money1."\n";
		}else{
			$c = "";
		}
		if($money2 != null){
			$f = $money2."\n";
		}else{
			$f = "";
		}
		$form->setTitle("§bHandel Anfrage");
		$form->setContent("§6Das ist eine Handels Anfrage von §d".$target->getName().". \n§6Entscheide ob du sie akzeptierst oder ablehnst.\n\n§aDer Spieler möchte Strassen:§f\n".$a.$b."\n§aDer Spieler möchte Geld:§f\n".$c."\n§eDer Spieler bietet dir Strassen:\n§f".$d.$e."\n§eDer Spieler bietet dir Geld:\n§f".$f);
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