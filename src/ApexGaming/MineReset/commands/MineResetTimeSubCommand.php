<?php

declare(strict_types=1);

namespace ApexGaming\MineReset\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use ApexGaming\MineReset\libs\CortexPE\Commando\args\IntegerArgument;
use ApexGaming\MineReset\libs\CortexPE\Commando\args\RawStringArgument;
use ApexGaming\MineReset\libs\CortexPE\Commando\BaseSubCommand;

use ApexGaming\MineReset\Main;
use ApexGaming\MineReset\mine\MineRegistry;

class MineResetTimeSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("setresettime", "", []);
		$this->setPermission("minereset.command");
	}
	
	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerArgument(0, new RawStringArgument("name"));
		$this->registerArgument(1, new IntegerArgument("seconds"));
	}

	/**
	 * @param CommandSender $p
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $p, string $aliasUsed, array $args): void{
		if(!$p instanceof Player) return;

		$mine = MineRegistry::getInstance()->getMine($args["name"]);

		if($mine === null){
			$p->sendMessage(Main::getPrefix() . "Invalid mine name");
			return;
		}

		$mine->resetTime = abs($args["seconds"]);
		$p->sendMessage(Main::getPrefix() . "Set the {$mine->name} reset time to " . number_format(abs($args["seconds"])) . " seconds");
	}
}
