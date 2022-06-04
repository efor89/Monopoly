<?php

namespace Monopoly\ui;

use pocketmine\event\{
	Listener,
	player\PlayerInteractEvent
};
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

class Gemeinschaftskarte{

	private $plugin;
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function GemeinschaftsKarte($player){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if ($result === null) {
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player->getName()." §ahat 2000$ Strafe gezahlt und keine Gemeinschaftskarte gezogen.");
				EconomyAPI::getInstance()->reduceMoney($player, 2000);
				return true;
			}
			switch ($result) {
				case 0:
					$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		            $gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		            $players = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
					$lastg = $gamecfg->get("lastg");
					$text = $config->getNested("Gemeinschaftsfeld".$lastg.".text");
					$Player1 = $players->get("player1");
		            $Player2 = $players->get("player2");
		            $Player3 = $players->get("player3");
		            $Player4 = $players->get("player4");
		            $player1 = Server::getInstance()->getPlayer($Player1);
	   	            $player2 = Server::getInstance()->getPlayer($Player2);
                    $player3 = Server::getInstance()->getPlayer($Player3);
	                $player4 = Server::getInstance()->getPlayer($Player4);
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player->getName()." hat eine GemeinschaftsKarte gezogen.");
					Server::getInstance()->broadcastMessage($text);
					if($lastg == 1){
						EconomyAPI::getInstance()->addMoney($player, 2000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 2){
						EconomyAPI::getInstance()->reduceMoney($player, 2000);
						$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 3){
						EconomyAPI::getInstance()->addMoney($player, 500);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 4){
						EconomyAPI::getInstance()->reduceMoney($player, 3000);
						$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 3000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 5){
						EconomyAPI::getInstance()->reduceMoney($player, 4000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 6){
						EconomyAPI::getInstance()->addMoney($player, 2000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 7){
						EconomyAPI::getInstance()->addMoney($player, 200);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 8){
						EconomyAPI::getInstance()->addMoney($player, 900);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 9){
						EconomyAPI::getInstance()->addMoney($player, 2000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 10){
						EconomyAPI::getInstance()->addMoney($player, 4000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 11){
						EconomyAPI::getInstance()->reduceMoney($player, 2000);
						$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 12){
						EconomyAPI::getInstance()->addMoney($player, 1000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 13){
						if($Player1 !== null and $Player2 !== null and $Player3 !== null and $Player4 !== null){
							if($Player1 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 3000);
							    EconomyAPI::getInstance()->reduceMoney($player4, 1000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player2 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 3000);
							    EconomyAPI::getInstance()->reduceMoney($player4, 1000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player3 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 3000);
							    EconomyAPI::getInstance()->reduceMoney($player4, 1000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
							}elseif($Player4 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 3000);
							    EconomyAPI::getInstance()->reduceMoney($player1, 1000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}
						}elseif($Player1 !== null and $Player2 !== null and $Player3 !== null and $Player4 == null){
							if($Player1 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player2 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player3 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
							}
						}elseif($Player1 !== null and $Player2 !== null and $Player3 == null and $Player4 !== null){
							if($Player1 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
								EconomyAPI::getInstance()->reduceMoney($player4, 1000);
							}elseif($Player2 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
								EconomyAPI::getInstance()->reduceMoney($player4, 1000);
							}elseif($Player4 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
							}
						}elseif($Player1 !== null and $Player2 == null and $Player3 !== null and $Player4 !== null){
							if($Player1 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player4, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player4 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player3 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player4, 1000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
							}
						}elseif($Player1 == null and $Player2 !== null and $Player3 !== null and $Player4 !== null){
							if($Player4 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player2 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player4, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player3 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 2000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
								EconomyAPI::getInstance()->reduceMoney($player4, 1000);
							}
						}elseif($Player1 !== null and $Player2 !== null and $Player3 == null and $Player4 == null){
							if($Player1 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
							}elseif($Player2 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
							}
						}elseif($Player1 !== null and $Player2 == null and $Player3 !== null and $Player4 == null){
							if($Player1 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player3 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
							}
						}elseif($Player1 !== null and $Player2 == null and $Player3 == null and $Player4 !== null){
							if($Player1 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player4, 1000);
							}elseif($Player4 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player1, 1000);
							}
						}elseif($Player1 == null and $Player2 !== null and $Player3 !== null and $Player4 == null){
							if($Player2 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player3 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
							}
						}elseif($Player1 == null and $Player2 !== null and $Player3 == null and $Player4 !== null){
							if($Player2 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player4, 1000);
							}elseif($Player4 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player2, 1000);
							}
						}elseif($Player1 == null and $Player2 == null and $Player3 !== null and $Player4 !== null){
							if($Player4 == $player->getName()){
							    EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player3, 1000);
							}elseif($Player3 == $player->getName()){
								EconomyAPI::getInstance()->addMoney($player, 1000);
								EconomyAPI::getInstance()->reduceMoney($player4, 1000);
							}
						}
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 14){
						EconomyAPI::getInstance()->reduceMoney($player, 1000);
						$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 1000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 15){
						EconomyAPI::getInstance()->addMoney($player, 4000);
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 16){
						EconomyAPI::getInstance()->addMoney($player, 2000);
						$gamecfg->set("lastg", 1);
						$gamecfg->save();
					}
				break;
			}
			switch ($result) {
				case 1:
				    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player->getName()." §ahat 2000$ Strafe gezahlt und keine Gemeinschaftskarte gezogen.");
					EconomyAPI::getInstance()->reduceMoney($player, 2000);
				break;
			}
		});
		$form->setTitle("§bGemeinschaftskarte");
		$form->setContent("§6Entscheide ob du eine Karte ziehst oder eine Strafe zahlst!");
        $form->addButton("§aKarte ziehen!");
		$form->addButton("§d2000$ Strafe zahlen!");						
		$form->addButton("§cSchließen");
		$form->sendToPlayer($player);
		return true;
	}
}