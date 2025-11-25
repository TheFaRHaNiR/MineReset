<?php

declare(strict_types=1);

namespace ApexGaming\MineReset\commands;

use pocketmine\command\CommandSender;

use ApexGaming\MineReset\libs\CortexPE\Commando\BaseCommand;

use ApexGaming\MineReset\Main;

class MineCommand extends BaseCommand{

	public function __construct(){
		parent::__construct(Main::getInstance(), "mine", "", ["minereset"]);
		$this->setPermission("minereset.command");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerSubCommand(new MineCreateSubCommand);
		$this->registerSubCommand(new MineInfoSubCommand);
		$this->registerSubCommand(new MineListSubCommand);
		$this->registerSubCommand(new MineResetSubCommand);
		$this->registerSubCommand(new MineAddBlockSubCommand);
		$this->registerSubCommand(new MineRemoveBlockSubCommand);
		$this->registerSubCommand(new MineResetTimeSubCommand);
		$this->registerSubCommand(new MineDeleteSubCommand);
		$this->registerSubCommand(new MineResetAllSubCommand);
		$this->registerSubCommand(new MineToggleDiffResetSubCommand);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{ }
}
