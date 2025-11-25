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

class MineRemoveBlockSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("removeblock", "", []);
		$this->setPermission("minereset.command");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerArgument(0, new RawStringArgument("name"));
		$this->registerArgument(1, new IntegerArgument("index"));
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

		if(isset($mine->blocks[$args["index"]])){
			$block = $mine->blocks[$args["index"]];

			unset($mine->blocks[$args["index"]]);
			$p->sendMessage(Main::getPrefix() . "Removed block: §c{$block->block->getName()}§7, chance: §c{$block->chance}");
		} else $p->sendMessage(Main::getPrefix() . "No blocks found at index §c{$args["index"]}");
	}
}
