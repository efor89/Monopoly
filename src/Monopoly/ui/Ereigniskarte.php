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

class Ereigniskarte{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function EreignisKarte($player){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if ($result === null) {
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player->getName()." §ahat 2000$ Strafe gezahlt und keine Ereigniskarte gezogen.");
				EconomyAPI::getInstance()->reduceMoney($player, 2000);
				return true;
			}
			switch ($result) {
				case 0:
					
				break;
			}
			switch ($result) {
				case 1:
				    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player->getName()." §ahat 2000$ Strafe gezahlt und keine Ereigniskarte gezogen.");
					EconomyAPI::getInstance()->reduceMoney($player, 2000);
				break;
			}
		});
		$form->setTitle("§bEreigniskarte");
		$form->setContent("§6Entscheide ob du eine Karte ziehst oder eine Strafe zahlst!");
        $form->addButton("§aKarte ziehen!");
		$form->addButton("§d2000$ Strafe zahlen!");						
		$form->addButton("§cSchließen");
		$form->sendToPlayer($player);
		return true;
	}
}