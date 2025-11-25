<?php

declare(strict_types=1);

namespace ApexGaming\MineReset\events;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

use ApexGaming\MineReset\mine\Mine;

class MineResetEvent extends Event implements Cancellable{
	use CancellableTrait;

	public function __construct(protected Mine $mine, protected bool $diff){ }

	/**
	 * @return Mine
	 */
	public function getMine(): Mine{
		return $this->mine;
	}

	/**
	 * @since 4.4.0
	 */
	public function hasDiff(): bool{
		return $this->diff;
	}
}