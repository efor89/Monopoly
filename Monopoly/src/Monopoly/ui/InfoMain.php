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
		$form->setContent("§6Schau dir die Spielregeln an oder sie dir infos über die Spieler an zb. Geld, Strassen!");
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
		if($Player1 != null){
		    $player1 = Server::getInstance()->getPlayer($Player1);
			$money1 = EconomyAPI::getInstance()->myMoney($player1);
			$street1 = $this->getPlayerStreetNames(Server::getInstance()->getPlayer($Player1));
		}else{
			$player1 = "";
			$money1 = "";
			$street1 = "";
		}
		if($Player2 != null){
	   	    $player2 = Server::getInstance()->getPlayer($Player2);
			$money2 = EconomyAPI::getInstance()->myMoney($player2);
			$street2 = $this->getPlayerStreetNames(Server::getInstance()->getPlayer($Player2));
		}else{
			$player2 = "";
			$money2 = "";
			$street2 = "";
		}
	    if($Player3 != null){
            $player3 = Server::getInstance()->getPlayer($Player3);
			$money3 = EconomyAPI::getInstance()->myMoney($player3);
			$street3 = $this->getPlayerStreetNames(Server::getInstance()->getPlayer($Player3));
	    }else{
			$player3 = "";
			$money3 = "";
			$street3 = "";
		}
	    if($Player4 != null){
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
		$form->setContent("§6Frei Parken: §f".$freiparken."$ \n\n§6Spieler Geld:\n§b".$Player1.": §f".$money1."$\n§b".$Player2.": §f".$money2."$\n§b".$Player3.": §f".$money3."$\n§b".$Player4.": §f".$money4."$\n\n§6Spieler Strassen:\n§b".$Player1.": \n§f".$street1."\n\n§b".$Player2.": \n§f".$street2."\n\n§b".$Player3.": \n§f".$street3."\n\n§b".$Player4.": \n§f".$street4."\n\n§6Strassen auf den eine Hypothek ist:\n§f".$this->getHypoStreets());
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
		$form->setContent("1. Das Spiel ist für 2-4 Spieler.\n2. Bei Einem Pasch muss man nochmal würfeln.\n3. Wer auf oder über Los kommt, bekommt 4000$.\n4. Strassen müssen gekauft werden, hat ein Spieler nicht genug Geld oder Kauft die strasse nicht, startet eine Gebots Runde.\n5. Ereignis und Gemeinschaftsfelder lösen verschiedene Aktionen aus.\n6. Wer auf Gehe in das Gefängnis Feld kommt, muss in das Gefängnis.\n7. Wer 3x hintereinander einen Pasch würfelt muss in das Gefängnis.\n8. Wer auf das Frei Parken Feld kommt, bekommt das Geld was bis dahin durch Strafen zusammen gekommen ist.\n9. Wer auf das Feld nur zu besuch kommt muss nicht in das Gefängnis.\n10. Wer die Miete oder Strafen nicht mehr bezahlen kann hat verlohren.\n11. Der Letzte Spieler der übrig ist gewinnt das Spiel.");
		$form->addButton("§eZurück");					
		$form->addButton("§cSchließen");
		$form->sendToPlayer($player);
		return true;
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
			$c = "6 = §0".$x."§r\n";
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
			$k = "16 = §0".$x."§r\n";
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
			$v = "26 = §0".$x."§r\n";
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
			$d1 = "36 = §0".$x."§r\n";
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
			$c = "6 = §b".$x."§r\n";
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
			$k = "16 = §0".$x."§r\n";
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
			$v = "26 = §0".$x."§r\n";
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
			$d1 = "36 = §0".$x."§r\n";
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