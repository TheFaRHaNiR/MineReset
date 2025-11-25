<?php

declare(strict_types=1);

namespace ApexGaming\MineReset\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use pocketmine\block\UnknownBlock;

use pocketmine\item\ItemBlock;
use pocketmine\item\StringToItemParser;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;

use ApexGaming\MineReset\libs\CortexPE\Commando\args\IntegerArgument;
use ApexGaming\MineReset\libs\CortexPE\Commando\args\RawStringArgument;
use ApexGaming\MineReset\libs\CortexPE\Commando\BaseSubCommand;

use ApexGaming\MineReset\Main;
use ApexGaming\MineReset\mine\MineBlock;
use ApexGaming\MineReset\mine\MineRegistry;

class MineAddBlockSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("addblock", "", []);
		$this->setPermission("minereset.command");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerArgument(0, new RawStringArgument("name"));
		$this->registerArgument(1, new RawStringArgument("blockID"));
		$this->registerArgument(2, new IntegerArgument("chance"));
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

		try{
			$block = StringToItemParser::getInstance()->parse($args["blockID"]) ?? LegacyStringToItemParser::getInstance()->parse($args["blockID"]);
		}catch(LegacyStringToItemParserException $e){
			$p->sendMessage(Main::getPrefix() . "Invalid block id");
			return;
		}

		if($args["chance"] > 100){
			$p->sendMessage(Main::getPrefix() . "Chance cannot be greater than 100");
			return;
		}

		if($block instanceof ItemBlock){
			$mine->blocks[] = new MineBlock($block->getBlock(), $args["chance"]);
		}

		$p->sendMessage(Main::getPrefix() . "Added new block to mine {$mine->name}, block: {$block->getName()}, chance: {$args["chance"]}");
	}
}
