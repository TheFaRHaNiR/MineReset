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
declare(strict_types=1);

namespace ApexGaming\MineReset\libs\CortexPE\Commando\constraint;

use ApexGaming\MineReset\libs\CortexPE\Commando\IRunnable;
use pocketmine\command\CommandSender;

abstract class BaseConstraint{

    /** @var IRunnable $context*/
    protected IRunnable $context;

    /**
     * BaseConstraint constructor.
     *
     * "Context" is required so that this new-constraint-system doesn't hinder getting command info
     *
     * @param IRunnable $context
     */
    public function __construct(IRunnable $context){
        $this->context = $context;
    }

    /**
     * @return IRunnable
     */
    public function getContext(): IRunnable{
        return $this->context;
    }

    /**
     * @param CommandSender $sender
     * @param string $aliasUsed
     * @param array $args
     * @return boolean
     */
    abstract public function test(CommandSender $sender, string $aliasUsed, array $args): bool;

    /**
     * @param CommandSender $sender
     * @param string $aliasUsed
     * @param array $args
     * @return void
     */
    abstract public function onFailure(CommandSender $sender, string $aliasUsed, array $args): void;

    /**
     * @param CommandSender $sender
     * @return boolean
     */
    abstract public function isVisibleTo(CommandSender $sender): bool;
}