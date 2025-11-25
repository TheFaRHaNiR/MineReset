<?php

/***
 *    ___                                          _
 *   / __\___  _ __ ___  _ __ ___   __ _ _ __   __| | ___
 *  / /  / _ \| '_ ` _ \| '_ ` _ \ / _` | '_ \ / _` |/ _ \
 * / /__| (_) | | | | | | | | | | | (_| | | | | (_| | (_) |
 * \____/\___/|_| |_| |_|_| |_| |_|\__,_|_| |_|\__,_|\___/
 *
 * Commando - A Command Framework virion for PocketMine-MP
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Written by @CortexPE <https://CortexPE.xyz>
 *
 */

declare(strict_types = 1);

namespace ApexGaming\MineReset\libs\CortexPE\Commando\args;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use function is_null;
use function preg_match;
use function strtolower;

class PlayerArgument extends BaseArgument{

    /**
     * @param boolean $optional
     * @param string|null $name
     */
	public function __construct(bool $optional = false, ?string $name = null){
		$name = is_null($name) ? "player" : $name;

		parent::__construct($name, $optional);
	}

    /**
     * @return string
     */
	public function getTypeName(): string{
		return "player";
	}

    /**
     * @return integer
     */
	public function getNetworkType(): int{
		return AvailableCommandsPacket::ARG_TYPE_TARGET;
	}

    /**
     * @param string $testString
     * @param CommandSender $sender
     * @return boolean
     */
	public function canParse(string $testString, CommandSender $sender): bool{
		return (bool) preg_match("/^(?!rcon|console)[a-zA-Z0-9_ ]{1,16}$/i", $testString);
	}

    /**
     * @param string $argument
     * @param CommandSender $sender
     * @return string
     */
	public function parse(string $argument, CommandSender $sender): string{
		return strtolower($argument);
	}
}