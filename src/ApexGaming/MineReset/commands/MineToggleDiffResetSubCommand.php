<?php

declare(strict_types=1);

namespace ApexGaming\MineReset\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use ApexGaming\MineReset\libs\CortexPE\Commando\BaseSubCommand;
use ApexGaming\MineReset\libs\CortexPE\Commando\args\BooleanArgument;
use ApexGaming\MineReset\libs\CortexPE\Commando\args\RawStringArgument;

use ApexGaming\MineReset\Main;
use ApexGaming\MineReset\mine\MineRegistry;

class MineToggleDiffResetSubCommand extends BaseSubCommand{
	
	public function __construct(){
		parent::__construct("diffreset", "", []);
		$this->setPermission("minereset.command");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerArgument(0, new RawStringArgument("name"));
		$this->registerArgument(1, new BooleanArgument("enabled", true));
	}

	/**
	 * @phpstan-param array{name: string}|array{name: string, enabled: bool} $args
	 */
	public function onRun(CommandSender $p, string $aliasUsed, array $args) : void{
		if(!$p instanceof Player) return;

		$mine = MineRegistry::getInstance()->getMine($args["name"]);

		if($mine === null){
			$p->sendMessage(Main::getPrefix() . "Invalid mine name");
			return;
		}

		$enabled = $mine->diffReset = $args["enabled"] ?? !$mine->diffReset;
		$p->sendMessage(Main::getPrefix() . "Set the {$mine->name} reset mode to " . ($enabled
				? "§aOn Changed"
				: "§rAlways"
			)
		);
	}
}
