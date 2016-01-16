# Dragon's Castle

> In what used to be a peaceful town, a fearsome dragon has appeared and has brought horror and become a threat to everybody. Merlina, a young witch, has decided to take the matter on her hands and has gone into the castle where the dragon resides to get rid of it and bring peace back to her people.

This is the source code to Dragon's Castle. One of the big things from this code is that you can try using it to make *your own* platformer game, and not have to cope with all the ugly details, like limitless two-way scrolling or slope physics. (well sorta, the engine could use some more flexibility and was designed only with small rooms instead of large stages)

**Currently the exact license for this code hasn't been decided yet, so if you want to use this for your own game feel free to contact me for arranging something. I'll definitely keep this at the very least compatible with the GPL, however!**

## How to play

You can get a prebuilt ROM by downloading the `witch.bin` file (make sure to choose "raw view" so the browser downloads it!).

Currently the controls are hardcoded. They're:

* **D-pad:** move
* **A button:** magic
* **B button:** attack
* **C button:** jump
* **Start button:** pause

Jump and attack are self-explanatory. Magic is a different kind of attack which gives you more points but it's a bit more risky to use. Currently magic doesn't work on every enemy (this will improve as development progresses).

You can reset the game by pressing A+B+C while paused.

## Building the ROM

The build script is meant for Linux systems. Sorry, Windows users! (you *could* try running it through Cygwin, but I don't guarantee anything) The source code was tested in a sandbox to ensure that it *actually builds* (the prebuilt binary belongs to this sandboxed test).

### Required toolchain

You'll need the following:

* asm68k (or something compatible, asm68k requires Wine)
* mdtiler (<http://tools.mdscene.net/>)
* tfi2eif (<http://tools.mdscene.net/>)
* pcm2ewf (<http://tools.mdscene.net/>)
* slz (<http://tools.mdscene.net/>)
* php (usually preinstalled on Linux)

Put all the executables inside the `tools` directory (or modify `build.sh` to point to the correct paths).

The game uses Echo as its sound engine. A build of Echo is included in the source code, but if you want to replace it you can always get the newest one from <http://echo.mdscene.net/>.

### Building

Once you sorted out the above, just run `build.sh` (make sure you run it from its directory!). If everything goes well (i.e. no assembly errors or the like), you should get two files, if they don't exist then something went wrong:

* **`witch.bin`:** the ROM, ready to play
* **`listing.txt`:** generated listing (useful for debugging)

## Localization

The game can be built in different languages. The ROM is hardcoded to a single language that can be selected from `buildme.68k` (make sure exactly *one* of these is set):

* **`LANG_EN`:** English
* **`LANG_ES`:** Spanish

### Strings

The game uses nul-terminated mostly-ASCII strings in a lot of places (e.g. menus). You can find the localizable strings in the `data/text` directory (one file for each language). Non-localizable strings (such as the copyright notice) are in `data/text.68k` itself instead.

As I said, strings are mostly-ASCII. Besides being uppercase-only and only a few symbols being present, the following characters differ from ASCII:

* `@` (`$40`) → `©`
* `#` (`$23`) → `Ñ`

### Game logo

The name of the game itself is localizable (e.g. "El Castillo del Dragón" in Spanish), and this means the graphics for its name in the title screen need to change. This is done by having completely different graphics for each language, then having `data/title.68k` choose one when the ROM is being built.

Places you need to touch:

* The relvant mdtiler script (`src-data/title/gfxbuild`)
* The file including the graphics (`data/title.68k`)
* The build script (`build.sh`)

## Debugging tools

You can enable or disable some debugging features when you build the ROM. These features can be turned on or off by modifying `buildme.68k`.

* **`DEBUG_INIT`:** fills VRAM with garbage at power on.
* **`DEBUG_MENU`:** enables the debug menu before starting the game.
* **`DEBUG_CONTROLS`:** enables debug-only controls during pause.
* **`DEBUG_HUD`:** shows debug information in the HUD.

### Debug menu

This menu shows up before you start playing and lets you tweak several values:

* **Starting stage:** lets you choose which room you want to start at.
* **Invincibility:** makes you invincible against enemies (you can still die by falling in pits).
* **Infinite lives:** keeps your lives counter permanently maxed out.
* **Sound test:** self-explanatory.

Note that the starting stage is currently *not* capped, so make sure to not select a value that isn't a valid room! (or the game will most likely crash)

### Debug controls

While the game is paused, you can press the following buttons:

* **↑ button:** Choose next stage (see top right of screen)
* **↓ button:** Choose previous stage (see top right of screen)
* **A button:** Go to the chosen stage
* **B button:** Toogle free move

To change to another stage, choose it using the D-pad, then press A (if you *don't* press A then the game won't switch stages!). Free move is enabled by pressing the B button, then unpause the game and use the D-pad to move around (press B while paused again to disable free move).

Note that if you want to reset the game (A+B+C while paused), make sure that the A button is the last one that gets pressed, or the game will restart the stage instead!

### Debug HUD

When this HUD is enabled, you'll see two more values below the score. The first one (with a percent sign) indicates the amount of CPU usage, though this value is correct only as long as it stays within a single frame. The second number (with a cross) indicates how many objects are in the current room.

## Making levels

The levels are made with Tiled and then passed through a converter (`tools/makestage.php`) to turn them into the game's internal format. Note that the converter is naïve so you need to use a very specific configuration:

* Levels must be in CSV format
* Tile size must be 32×32
* First pattern table must be `src-data/tileset/tileset.png` with 32×32 tiles
* Second pattern table must be `src-data/tileset/objset.png` with 64×64 tiles
* First layer is the tilemap layer
* Second layer is the object layer

The minimum size for a level is 10×7 tiles (the size of the screen) and the maximum size is 255×255 tiles. Also note that objects will be aligned (to nearest) to 16 pixels, this should give you some wiggle room when placing them with the mouse.

### Starting place

The player will spawn wherever you place a Merlina object. Don't place more than one Merlina or results will be undefined!

### Doors

You can make a door just by placing the relevant object. This alone will create a closed door. To make an open door (one that goes to another room), you should change its name to "`STAGE_`" followed by the room's name (e.g. "`STAGE_BASEMENT2`" takes you to the second basement room). Make sure the stage exists in the ROM! (otherwise keep the door closed until it exists)

**Limitation:** You can't make a door that goes back to the first room (this will result in a closed door instead).

### Tweaking objects

Several other objects can have their behavior altered through their name (like the doors above). By general rule, you should give them names whenever possible. These objects are:

* Spiders tell how far they can fall through their name. Enter the distance in pixels (e.g. 88 = fall down up to 88 pixels).
* Spinning spikeballs can have their initial angle defined in their name. Enter a number between 0 and 255.
* Cogwheels will spin counterclockwise if their name is 0 or clockwise if their name is 1.

### Custom tiles

The converter will just copy custom tiles as-is. However, it needs to know what collision type to use for each tile, so if you add any custom tiles you need to let it know. Near the beginning of `tools/makestage.php` you'll find an array called `$colltypes`; this array contains the collision type for each tile. The types are:

* **`$00`:** no collision
* **`$01`:** solid (e.g. wall)
* **`$02`:** one-way floor
* **`$FE`:** slope (downhill to the left)
* **`$FF`:** slope (downhill to the right)

You also will want to add the tile to the pattern table so you can see it in Tiled. To do this, create a PNG with the tile with the respective number as filename in `src-data/tileset` (make sure the hex letters are in uppercase!), then call `tools/tileset.php`. Tiled should reload the pattern table immediately even if the stage is already opened :)

Finally you need to let the game know. The tile patterns are in `data/tileset.68k`. If you need new graphics then you'll need to modify `src-data/tileset/gfxbuild` accordingly and then update `VramTileset` in both `src-68k/variables.68k` and `src-68k/ingame.68k`.

### Slopes

Internally, slopes are actually platform objects (because handling them through map collision never worked right). However, the level editor will detect slope tiles and automatically generate the relevant objects for them.

Note that this means that you need to take them into account towards the object count.

### Special features

The converter will detect special features in the stages depending on what it finds inside. Note that in many cases these are usually *not* compatible with each other, and results will be impredictable if you attempt so:

* If there's a water tile, it'll enable the underwater raster effect and load the underwater palettes. Note that the surface of the water must be always at the same height through all the room.

* If there's one of the bluish tiles used when going in the walls outside the castle, it'll enable the fog parallax and load the fog palettes.

* The levels with the `lava` filenames will load the lava graphics and palettes.

* The levels with the `mirror` filenames will load the mirror palettes and create the reflection object.

## Tweaking the game

*Note that speeds are indicated in 1/256ths of a pixel per frame (e.g. to move 2.5 pixels per second you need to use `$280`)*

### Copyright

Look up for `StrCopyright` in the file `data/text.68k`. Remember to write "`@`" when you want to write "`©`" (e.g. "`@2016`"). Currently the text is hardcoded to 15 characters (it will misalign otherwise), this needs to be fixed :) (go change `src-68k/title.68k` meanwhile)

If you want to remove the copyright notice in the title screen, go to `buildme.68k` and disable `SHOW_COPYRIGHT`.

### Player physics

You can change the constants at the beginning of `src-68k/player.68k` to modify the player physics:

* **`MAX_SPEED`:** the speed at which the player runs
* **`ACCEL_SPEED`:** how quickly the player accelerates to run
* **`ACCEL_FALL`:** how quickly the player falls (i.e. her "weight")
* **`JUMP_FORCE`:** how far the player jumps (*initial speed*, peak height is hard to calculate!)
* **`ATTACK_TIME`:** how long does it take to attack with the broom
* **`MAGIC_TIME`:** how long does it take to use magic normally
* **`SUPERMAGIC_TIME`:** how long does it take to use magic with the yellow potion
* **`RECOIL_SPEED`:** like `MAX_SPEED` when recoiling from getting hurt
* **`RECOIL_FORCE`:** like `JUMP_FORCE` when recoiling from getting hurt

The player's collision size can be changed with the following (relative to the player coordinates), although we need deeper testing to ensure they're working exactly as intended everywhere (i.e. no value is accidentally hardcoded somewhere):

* **`PLAYER_X1`:** left boundary
* **`PLAYER_Y1`:** top boundary
* **`PLAYER_X2`:** right boundary
* **`PLAYER_Y2`:** bottom boundary

It's worth noting that many objects have similar parameters in their respective files, so you may want to check those too! :)

### New objects

Each object consists of up to three subroutines:

* Initialization (when object is created)
* Running (when a frame of the game logic is executed)
* Drawing (when the object's sprite is drawn)

All those are optional (nothing happens when a subroutine is missing). The init subroutine gets the object pointer in `a6` and is allowed to change `d5-d7` and `a4-a6`. The run and draw subroutines get the object pointer in `a0` and can modify any register except that one.

To create a new object, make the relevant subroutines (those that are needed) and then in `src-68k/objects.68k`:

* Add an entry in the `OBJTYPE_*` list near the beginning. *Remember its order in the list*
* If you have an init function, go to `AddObject` and add it to the list there (put `@Null` if not needed)
* If you have a run function, go to `RunAllObjects` and add it to the list there (put `@Null` if not needed)
* If you have a draw function, go to `DrawAllObjects` and add it to the list there (put `@Null` if not needed)

### Scoring

The score awarded by different actions can be found in the list of constants at the beginning of `buildme.68k`. *Note that these values are BCD, so keep the `$` prefix in the numbers!* Also note that the score is capped at 99999999 points.

* **`SCORE_BROOM`:** defeating an enemy with a normal attack
* **`SCORE_MAGIC`:** defeating an enemy with a magic attack
* **`SCORE_FREEZE`:** *(for multiplyer, currently not implemented)*
* **`SCORE_ITEM`:** collecting an item

## Game engine

Some useful subroutines for you, unless otherwise stated assume they can modify `d5-d7` and `a4-a6` on return:

* Player interaction

    * **`CollidesPlayer`:** checks if an object collides with the player (it must have its hitbox defined). Pass the pointer to the object in `a6.l`. On return, check `d7.w`: it's non-zero if there's a collision, or zero otherwise.

    * **`CollidesAttack`:** same as above, but checks for collision against the broom.

    * **`CollidesMagic`:** same as above, but checks for collision against magic attacks.
    
    * **`HurtPlayer`:** hurts the player (e.g when an enemy touches her). Pass your coordinates in `d7.w` and `d6.w` (X and Y) so the game knows in which direction she should recoil. This subroutine takes into account when she's flashing and won't hurt her in that case (so feel free to call it continuously).
    
    * **`KillPlayer`:** immediately sets the player health to zero. Use this for instant kills.
    
    * **`FacePlayer`:** call this to make an object face the player. Pass the pointer to the object in `a6.l`.

* Object creation

    * **`AddObject`:** creates a new object. Pass the coordinates in `d7.w` and `d6.w` (X and Y), the object type in the low byte of `d5.w` and the flags in the high byte. It returns a pointer to the new object in `a6.l`, in case you want to mess with it further.

    * **`DestroySelf`:** how you're meant to make an object self-destruct. Jump here using `bra` or `jmp` (i.e. *not* a subroutine jump) to get rid of the object.

* Map collision

    * **`TileAt`:** retrieves whatever tile is at certain location. Give the position in `d7.w` and `d6.w` (X and Y, measured in *pixels*). On return, `d7.w` will contain the graphic used, while `d6.w` will contain the collision type (e.g. `COLL_SOLID` for a wall).

* Miscellaneous

    * **`PlaySFX`:** plays a sound effect. The SFX ID goes in `d7.b`. (see `SFX_*` in `data/sound.68k` for a full list). No registers are modified. Note that a higher priority SFX (higher value) will always prevent a lower priority SFX from playing!

    * **`Rand`:** returns a random 16-bit value in `d7.w`. *No other registers are changed.* Note that the RNG uses actual noise from the hardware so the values are *not* predictable.
    
    * **`Rand32`:** same as above, but generates a random 32-bit value in `d7.l`.

## Known bugs

Aside from the obvious *it's not done yet* kind of bugs:

* Right now the game relies on asm68k which is a proprietary assembler, we need to find a free compatible replacement. The problem is that nearly all assemblers use their own syntax for anything beyond the basics...

    * As far as I know, asmx should be mostly (if not fully) compatible (a few directives may need to be replaced). The problem is that last I checked asmx was notoriously buggy and can end up generating an incorrect binary!

* The player physics are not fool-proof. Then again this is a 7.67MHz 16-bit processor, so what can we expect?

* Sprite drawing routines are taking up a lot of time (a huge chunk of CPU time is spent in drawing objects o_O), this brought up problems in the vertical room with many platforms and the cogwheels as CPU time skyrocketed to up to 94% and I had to make the relevant objects explicitly check for screen boundaries.

    * The problem is not `SortSprites` (that takes up a few scanlines), the bulk of the time is spent while `DrawAllObjects` (and whatever it calls) is running. You can even check this by enabling `DEBUG_DRAWUSAGE` from `buildme.68k` (this is only usable from some emulators and not real hardware though, beware!).

    * Removing the boundary checks from `AddSprite` is not that useful because that'd only move the check to the objects themselves instead.

### Non-bugs

* When `DEBUG_CONTROLS` is on, you have to press A last when doing the A+B+C sequence during pause. This is not a problem for a release build (where this would be disabled) and it's the only way to allow both inputs to work simultaneously.

* Often you may see garbage colors in the palette. This happens when the game doesn't need all the colors so it just lets the code load beyond what's actually used. When it doesn't use a palette row at all it just loads data from `$000000` (which is the 68000 vectors!).
