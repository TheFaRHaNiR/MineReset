<?php

declare(strict_types=1);

namespace ApexGaming\MineReset\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use ApexGaming\MineReset\libs\CortexPE\Commando\BaseSubCommand;

use ApexGaming\MineReset\Main;
use ApexGaming\MineReset\mine\MineRegistry;

class MineListSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("list", "", []);
		$this->setPermission("minereset.command");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
	}

	/**
	 * @param CommandSender $p
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $p, string $aliasUsed, array $args): void{
		if(!$p instanceof Player) return;

		$all = MineRegistry::getInstance()->getAllMines();

		$p->sendMessage(Main::getPrefix() . "§7all mines: ");
		foreach($all as $mine){
			$p->sendMessage("   §7- §a{$mine->name}");
		}
	}
}
