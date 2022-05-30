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

class InfoMain{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function InfoMain($player){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if ($result === null) {
				return true;
			}
			switch ($result) {
				case 0:
					$this->Regeln($player);
				break;
			}
			switch ($result) {
				case 1:
				    $this->Infos($player);
				break;
			}
		});
		$form->setTitle("§bInfo's Menü");
		$form->setContent("§6ESchau dir die Spielregeln an oder sie dir infos über die Spieler an zb. Geld, Strassen!");
        $form->addButton("§aSpielregeln");
		$form->addButton("§eSpieler Info's");					
		$form->addButton("§cSchließen");
		$form->sendToPlayer($player);
		return true;
	}
	
	public function Infos($player){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if ($result === null) {
				return true;
			}
			switch ($result) {
				case 0:
				    $this->InfoMain($player);
				break;
			}
		});
		$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		$players = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
		$Player1 = $players->get("player1");
		$Player2 = $players->get("player2");
		$Player3 = $players->get("player3");
		$Player4 = $players->get("player4");
		if($Player1 !== null){
		    $player1 = Server::getInstance()->getPlayer($Player1);
			$money1 = EconomyAPI::getInstance()->myMoney($player1);
			$street1 = $this->getPlayerStreetNames(Server::getInstance()->getPlayer($Player1));
		}else{
			$player1 = "";
			$money1 = "";
			$street1 = "";
		}
		if($Player2 !== null){
	   	    $player2 = Server::getInstance()->getPlayer($Player2);
			$money2 = EconomyAPI::getInstance()->myMoney($player2);
			$street2 = $this->getPlayerStreetNames(Server::getInstance()->getPlayer($Player2));
		}else{
			$player2 = "";
			$money2 = "";
			$street2 = "";
		}
	    if($Player3 !== null){
            $player3 = Server::getInstance()->getPlayer($Player3);
			$money3 = EconomyAPI::getInstance()->myMoney($player3);
			$street3 = $this->getPlayerStreetNames(Server::getInstance()->getPlayer($Player3));
	    }else{
			$player3 = "";
			$money3 = "";
			$street3 = ;
		}
	    if($Player4 !== null){
	        $player4 = Server::getInstance()->getPlayer($Player4);
			$money4 = EconomyAPI::getInstance()->myMoney($player4);
			$street4 = $this->getPlayerStreetNames(Server::getInstance()->getPlayer($Player4));
		}else{
			$player4 = "";
			$money4 = "";
			$street4 = "";
		}
		$form->setTitle("§bInfo's");
		$freiparken = $gamecfg->get("freiparken");;
		$form->setContent("§6Frei Parken: §f".$freiparken."$ \n\n§6Spieler Geld:\n§f".$Player1.": ".$money1."$\n§f".$Player2.": ".$money2."$\n§f".$Player3.": ".$money3."$\n§f".$Player4.": ".$money4."$\n\n§6Spieler Strassen:\n§f".$Player1.": \n§f".$street1."\n\n§f".$Player2."\n: \n§f".$street2."\n\n§f".$Player3.": \n§f".$street3."\n\n§f".$Player4.": \n§f".$street4);
        $form->addButton("§eZurück");
		$form->addButton("§cSchließen");
		$form->sendToPlayer($player);
		return true;
	}
	
	public function Regeln($player){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if ($result === null) {
				return true;
			}
			switch ($result) {
				case 0:
				    $this->InfoMain($player);
				break;
			}
		});
		$form->setTitle("§bSpiel Regeln");
		$form->setContent("1. Das Spiel ist für 1-4 Spieler.\n2. Bei Einem Pasch muss man nochmal würfeln.\n3. Wer auf oder über Los kommt, bekommt 4000$.\n4.Strassen müssen gekauft werden, hat ein Spieler nicht genug Geld startet eine Gebots Runde.\n5. Ereignis und Gemeinschaftsfelder lösen verschiedene Aktionen aus.\n6. Wer auf Gehe in das Gefängnis Feld kommt, muss in das Gefängnis.\n7. Wer auf das Frei Parken Feld kommt, bekommt das Geld was bis dahin durch Strafen zusammen gekommen ist.\n8. Wer auf das Feld nur zu besuch kommt muss nicht in das Gefängnis.\n9. Wer die Miete oder Strafen nicht mehr bezahlen kann hat verlohren.\n10. Der Letzte Spieler der übrig ist ginnt das Spiel.");
		$form->addButton("§eZurück");					
		$form->addButton("§cSchließen");
		$form->sendToPlayer($player);
		return true;
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