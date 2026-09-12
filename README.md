<div align="center">

# MineReset

**Automatic mine management and reset system for PocketMine-MP**

</div>

---

## Overview

MineReset is a mine management plugin for PocketMine-MP that allows server administrators to create, configure, and automatically reset custom mining areas.

Each mine can have its own reset interval, block distribution, reset mode, and protected region. Mines are stored persistently and restored automatically when the server starts.

## Features

| Feature                        | Description                                                                                           |
| ------------------------------ | ----------------------------------------------------------------------------------------------------- |
| **Mine management**            | Create, inspect, modify, reset, and delete mines directly in-game.                                    |
| **Automatic resets**           | Mines automatically reset after their configured reset interval.                                      |
| **Custom block distribution**  | Configure which blocks are generated inside each mine and their individual chances.                   |
| **Weighted block selection**   | Block chances are used to create a weighted random block distribution during resets.                  |
| **Manual reset**               | Reset individual mines or every configured mine with a command.                                       |
| **Reset timer**                | Configure an independent reset interval for every mine.                                               |
| **Changed-only reset mode**    | Optionally reset a mine only when blocks inside it have been changed.                                 |
| **Explosion detection**        | Explosions inside a mine are detected as changes to the mine.                                         |
| **Player handling**            | Players inside a mine can automatically be teleported to the world's safe spawn when the mine resets. |
| **Reset announcements**        | Broadcast configurable messages when a mine starts resetting.                                         |
| **Persistent storage**         | Mine definitions are automatically saved to a JSON configuration file.                                |
| **Asynchronous reset process** | Mine resets are processed incrementally to avoid performing the entire operation in a single tick.    |
| **Configuration control**      | Control reset performance, player teleportation, prefixes, and reset announcements from `config.yml`. |

## Requirements

| Requirement   | Version |
| ------------- | ------- |
| PocketMine-MP | 5.36.0  |
| API           | 5.36.0  |

No external plugin dependencies are required.

## Installation

1. Download the latest `MineReset.phar`.
2. Place the file inside your server's `plugins/` directory.
3. Start or restart the server.
4. The default `config.yml` will be generated automatically.
5. Configure the plugin according to your server requirements.

## Configuration

The main configuration file is located at:

```text
plugin_data/MineReset/config.yml
```

Example:

```yaml
# DO NOT TOUCH!
config-version: "1.0.0"

# Plugin prefix
prefix: "§7[§b§lMine§dReset§r§7] "

# How many blocks should be replaced before yielding.
# 20 ticks = 1 second.
blockReplaceTick: 3000

# Teleport players inside the mine to the world's safe spawn
# when the mine is reset.
teleport-to-spawn: true

messages:
  mine-reset-announcement: "{prefix} §bMine §a{mine}§b is resetting."
```

### Configuration Options

| Option                             |          Default | Description                                                                 |
| ---------------------------------- | ---------------: | --------------------------------------------------------------------------- |
| `prefix`                           | MineReset prefix | Prefix used by plugin messages.                                             |
| `blockReplaceTick`                 |           `3000` | Number of blocks replaced before the reset process yields.                  |
| `teleport-to-spawn`                |           `true` | Teleports players inside the mine to the world's safe spawn during a reset. |
| `messages.mine-reset-announcement` |          Enabled | Message broadcast when a mine begins resetting.                             |

Supported message placeholders:

| Placeholder | Description   |
| ----------- | ------------- |
| `{prefix}`  | Plugin prefix |
| `{mine}`    | Mine name     |

## Creating a Mine

Use:

```text
/mine create <name> <reset-time>
```

After running the command, the plugin will ask you to select two positions.

Break one block to select the first position, then break another block to select the second position.

Example:

```text
/mine create MineA 300
```

This creates a mine named `MineA` with a reset interval of 300 seconds.

The selection process automatically expires after one minute.

To cancel the selection process, use:

```text
cancel38
```

## Adding Blocks

After creating a mine, blocks can be added using:

```text
/mine addblock <name> <block> <chance>
```

Example:

```text
/mine addblock MineA stone 60
/mine addblock MineA coal_ore 25
/mine addblock MineA iron_ore 10
/mine addblock MineA diamond_ore 5
```

The configured chance determines the weight of each block when the mine is regenerated.

The maximum configured chance for an individual block is `100`.

## Removing Blocks

Remove a block entry from a mine using its index:

```text
/mine removeblock <name> <index>
```

Example:

```text
/mine removeblock MineA 2
```

Use `/mine info <name>` to view the indexes of the configured blocks.

## Resetting Mines

Reset a specific mine manually:

```text
/mine reset <name>
```

Example:

```text
/mine reset MineA
```

Reset every registered mine:

```text
/mine resetall
```

Manual resets use the same reset process as automatic resets.

## Reset Timer

Change the reset interval of an existing mine:

```text
/mine setresettime <name> <seconds>
```

Example:

```text
/mine setresettime MineA 600
```

This changes the reset interval to 600 seconds.

The value is automatically treated as an absolute value.

## Changed-Only Reset

MineReset supports two reset modes.

### Always

The mine resets whenever its configured reset timer expires.

### On Changed

The mine resets only if blocks inside the mine have been changed since its previous reset.

Enable or disable this mode with:

```text
/mine diffreset <name> <true|false>
```

Example:

```text
/mine diffreset MineA true
```

When enabled, the mine waits for a block break or an explosion inside its region before allowing the next timed reset.

This can be useful for mines that should not regenerate when nobody has mined them.

## Mine Information

View detailed information about a mine:

```text
/mine info <name>
```

The information includes:

* World
* First position
* Second position
* Reset interval
* Time remaining until the next reset
* Configured blocks
* Block chances

Example:

```text
/mine info MineA
```

## Mine List

Display all registered mines:

```text
/mine list
```

This shows every mine currently registered by MineReset.

## Deleting a Mine

Delete an existing mine:

```text
/mine delete <name>
```

Example:

```text
/mine delete MineA
```

The mine is removed from the active registry and will no longer be processed.

## Commands & Permissions

| Command                                  | Description                    | Permission          |
| ---------------------------------------- | ------------------------------ | ------------------- |
| `/mine create <name> <seconds>`          | Create a new mine              | `minereset.command` |
| `/mine info <name>`                      | Display mine information       | `minereset.command` |
| `/mine list`                             | List all mines                 | `minereset.command` |
| `/mine reset <name>`                     | Reset a specific mine          | `minereset.command` |
| `/mine resetall`                         | Reset all mines                | `minereset.command` |
| `/mine addblock <name> <block> <chance>` | Add a block to a mine          | `minereset.command` |
| `/mine removeblock <name> <index>`       | Remove a block entry           | `minereset.command` |
| `/mine setresettime <name> <seconds>`    | Change reset interval          | `minereset.command` |
| `/mine diffreset <name> <true\|false>`   | Toggle changed-only reset mode | `minereset.command` |
| `/mine delete <name>`                    | Delete a mine                  | `minereset.command` |

The `minereset.command` permission defaults to `op`.

## Reset Process

When a mine is reset, MineReset:

1. Checks whether the mine is allowed to reset.
2. Fires the `MineResetEvent`.
3. Announces the reset using the configured message.
4. Detects players inside the mine region.
5. Teleports players to the world's safe spawn when enabled.
6. Generates a weighted random block distribution.
7. Replaces the blocks inside the configured region.
8. Processes the replacement incrementally to avoid blocking the server with the entire operation at once.
9. Updates the mine's reset timestamp.

The reset region is defined by the two positions selected during mine creation.

## Events

MineReset provides a cancellable reset event:

```php
ApexGaming\MineReset\events\MineResetEvent
```

The event provides access to the mine being reset and whether the reset was triggered after detecting a change.

Example:

```php
use ApexGaming\MineReset\events\MineResetEvent;

public function onMineReset(MineResetEvent $event): void{
    $mine = $event->getMine();

    if($mine->name === "MineA"){
        $event->cancel();
    }
}
```

This allows other plugins to control or prevent mine resets.

## Data Storage

Mine definitions are stored automatically in:

```text
plugin_data/MineReset/config.json
```

The stored data includes:

* Mine name
* World
* Position 1
* Position 2
* Block configuration
* Reset interval
* Last reset timestamp
* Changed-only reset setting

The data is saved automatically when the plugin is disabled.

## Building from Source

MineReset includes its required libraries inside the project.

Build the plugin using your preferred PocketMine-MP plugin build workflow.

The resulting plugin can be installed as:

```text
MineReset.phar
```

## License

© ApexGaming — made by **TheFaRHaNiR**
