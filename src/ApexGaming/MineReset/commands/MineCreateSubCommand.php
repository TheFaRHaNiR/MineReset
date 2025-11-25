<?php

declare(strict_types=1);

namespace ApexGaming\MineReset\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerChatEvent;

use pocketmine\utils\TextFormat;
use pocketmine\world\Position;

use ApexGaming\MineReset\libs\SOFe\AwaitGenerator\Await;

use ApexGaming\MineReset\libs\CortexPE\Commando\args\IntegerArgument;
use ApexGaming\MineReset\libs\CortexPE\Commando\args\RawStringArgument;
use ApexGaming\MineReset\libs\CortexPE\Commando\BaseSubCommand;

use ApexGaming\MineReset\Main;
use ApexGaming\MineReset\mine\Mine;
use ApexGaming\MineReset\mine\MineRegistry;

class MineCreateSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("create", "", []);
		$this->setPermission("minereset.command");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerArgument(0, new RawStringArgument("name"));
		$this->registerArgument(1, new IntegerArgument("reset time"));
	}

	/**
	 * @param CommandSender $p
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $p, string $aliasUsed, array $args): void{
		if(!$p instanceof Player) return;

		$name = TextFormat::clean($args["name"]);

		Await::f2c(function() use ($p, $args, $name){

			$p->sendMessage(Main::getPrefix() . "Break a block to select the first position");
			if(($pos1 = yield from $this->getMinePosition($p)) !== false){
				$p->sendMessage(Main::getPrefix() . "Break a block to select the second position");
				if(($pos2 = yield from $this->getMinePosition($p)) !== false){
					if(!$p->isOnline()) return;

					$mine = new Mine($name, $pos1, $pos2, $p->getWorld()->getFolderName(), [], abs($args["reset time"]), time());
					MineRegistry::getInstance()->addMine($mine);
					$p->sendMessage(Main::getPrefix() . "§aSuccessfully created mine $name. §7(Use /mine addblock to add blocks)");
					return;
				}
			}

			if($p->isOnline()){
				$p->sendMessage(Main::getPrefix() . "§cMine creation has been cancelled");
			}
		});

	}

	/**
	 * @param Player $p
	 *
	 * @return Position|bool
	 */
	public function getMinePosition(Player $p){
		$std = Main::getInstance()->getStd();


		$data = yield from Await::race(
			[
				$std->sleep(20 * 60),
				$std->awaitEvent(BlockBreakEvent::class, fn(BlockBreakEvent $e) : bool => $e->getPlayer()->getName() === $p->getName(), false, EventPriority::MONITOR, true),
				$std->awaitEvent(PlayerChatEvent::class, fn(PlayerChatEvent $e) : bool => $e->getPlayer()->getName() === $p->getName() && $e->getMessage() === "cancel38", false, EventPriority::MONITOR, true)
			]
		);

		if(!$p->isOnline()) return false;

		if($data[0] === 0){
			return false;
		}

		if($data[0] === 2){
			return false;
		}

		return $data[1]->getBlock()->getPosition()->floor();
	}
}
