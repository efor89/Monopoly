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

class hypothek{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function Hypothek($player){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if ($result === null) {
				return true;
			}
			switch ($result) {
				case 0:
					
				break;
			}
			switch ($result) {
				case 1:
				    
				break;
			}
		});
		$form->setTitle("§bHypothek");
		$form->setContent("§6Nimm eine Hypothek auf oder bezahle eine ab!");
        $form->addButton("§aAbzahlen");
		$form->addButton("§dAufnehmen");						
		$form->addButton("§cSchließen");
		$form->sendToPlayer($player);
		return true;
	}
}