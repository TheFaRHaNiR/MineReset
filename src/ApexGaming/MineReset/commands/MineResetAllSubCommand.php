<?php

declare(strict_types=1);

namespace ApexGaming\MineReset\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use ApexGaming\MineReset\libs\SOFe\AwaitGenerator\Await;

use ApexGaming\MineReset\libs\CortexPE\Commando\BaseSubCommand;

use ApexGaming\MineReset\Main;
use ApexGaming\MineReset\mine\MineRegistry;

class MineResetAllSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("resetall", "", []);
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
		Await::f2c(function() use ($p){
			foreach(MineRegistry::getInstance()->getAllMines() as $mine){
				$this->msg($p, Main::getPrefix() . "Trying to reset mine §c{$mine->name}");


				$result = yield from $mine->tryReset();

				if($result === true){
					$this->msg($p, Main::getPrefix() . "Mine §c{$mine->name}§7 has been reset.");
				}else $this->msg($p, Main::getPrefix() . "Failed to reset mine §c{$mine->name}");
			}
		});
	}

	public function msg(CommandSender $sender, string $msg){
		if($sender instanceof Player && $sender->isOnline()){
			$sender->sendMessage($msg);
		}else $sender->sendMessage($msg);
	}
}
