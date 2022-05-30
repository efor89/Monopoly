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
					$text = $config->getNested("Gemeinschaftsfeld".$lastg".text");
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player->getName()." hat eine GemeinschaftsKarte gezogen.");
					Server::getInstance()->broadcastMessage($text);
					if($lastg == 1){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 2){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 3){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 4){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 5){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 6){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 7){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 8){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 9){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 10){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 11){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 12){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 13){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 14){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 15){
						$gamecfg->set("lastg", $gamecfg->get("lastg") + 1);
						$gamecfg->save();
					}elseif($lastg == 16){
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