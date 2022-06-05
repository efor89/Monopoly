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

class AbbauenUI{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function AbbauenUI($player){
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
			if($data[1] > 40 or $data[1] < 1){
				$player->sendMessage("§bMono§6poly: §cGib eine gültige Zahl an zwischen 1-40.");
				return true;
			}
			$feld = $data[1];
			if($feld == 6 or $feld == 16 or $feld == 26 or $feld == 36 or $feld == 13 or $feld == 29 or $feld == 1 or $feld == 3 or $feld == 5 or $feld == 8 or $feld == 11 or $feld == 18 or $feld == 21 or $feld == 23 or $feld == 31 or $feld == 34 or $feld == 37 or $feld == 39){
				$player->sendMessage("§bMono§6poly: §cDu kannst hier nichts bauen.");
				return true;
			}
			if($this->isPlayerStreet($player, $data[1]) === "no"){
				$player->sendMessage("§bMono§6poly: §cDie Strasse gehört dir nicht oder sie existiert nicht.");
				return true;
			}
			if($gamecfg->get($data[1]."haus") < 1){
				$player->sendMessage("§bMono§6poly: §cDu kannst hier kein Haus abbauen da auf der Strasse kein Haus steht.");
				return true;
			}
			$kosten = $config->getNested($data[1].".house");
			EconomyAPI::getInstance()->addMoney($player, $kosten / 2);
			$x = $config->getNested($data[1].".x".$gamecfg->get($data[1]."haus"));
			$z = $config->getNested($data[1].".z".$gamecfg->get($data[1]."haus"));
			$y = 5;
			$y1 = 6;
			if($gamecfg->get($data[1]."haus") < 5){
			    $player->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(0, 0));
			}else{
				$x1 = $config->getNested($data[1].".x".$gamecfg->get($data[1]."haus") + 1);
			    $z1 = $config->getNested($data[1].".z".$gamecfg->get($data[1]."haus") + 1);
				$player->getLevel()->setBlock(new Vector3($x, $y1, $z), Block::get(0, 0));
				$player->getLevel()->setBlock(new Vector3($x1, $y1, $z1), Block::get(0, 0));
			}
			$gamecfg->set($data[1]."haus", $gamecfg->get($data[1]."haus") - 1);
			$gamecfg->save();
			Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player->getName()." §ahat auf der Strasse §d".$config->getNested($data[1].".name")." §aein haus abgebaut.");
		});
		$form->setTitle("§bAbbauen Menü");
		$form->addLabel("§6Deine Strassen sind:\n§f".$this->getPlayerStreetNames($player));
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