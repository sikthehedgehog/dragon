# Making levels

The levels are made with Tiled and then passed through a converter (`tools/makestage.php`) to turn them into the game's internal format. Note that the converter is naïve so you need to use a very specific configuration:

* Levels must be in CSV format
* Tile size must be 32×32
* First pattern table must be `src-data/tileset/tileset.png` with 32×32 tiles
* Second pattern table must be `src-data/tileset/objset.png` with 64×64 tiles
* First layer is the tilemap layer
* Second layer is the object layer

The minimum size for a level is 10×7 tiles (the size of the screen) and the maximum size is 255×255 tiles. Also note that objects will be aligned (to nearest) to 16 pixels, this should give you some wiggle room when placing them with the mouse.

## Starting place

The player will spawn wherever you place a Merlina object. Don't place more than one Merlina or results will be undefined!

## Doors and end blocks

You can make a door just by placing the relevant object. This alone will create a closed door. To make an open door (one that goes to another room), you should change its name to "`STAGE_`" followed by the room's name (e.g. "`STAGE_BASEMENT2`" takes you to the second basement room). Make sure the stage exists in the ROM! (otherwise keep the door closed until it exists)

End blocks work similarly: place an end block object, then set the target stage in its name. The player will be sent to that stage whenever she collides with the block.

**Limitation:** You can't make a door that goes back to the first room (this will result in a closed door instead). This limitation doesn't apply to end blocks.

## Tweaking objects

Several other objects can have their behavior altered through their name (like the doors above). By general rule, you should give them names whenever possible. These objects are:

* Spiders tell how far they can fall through their name. Enter the distance in pixels (e.g. 88 = fall down up to 88 pixels).
* Piranhas and knights will start facing right when their name is 0 and left when their name is 1.
* Spinning platforms and spikeballs can have their initial angle defined in their name. Enter a number between 0 and 255.
* Cogwheels will spin counterclockwise if their name is 0 or clockwise if their name is 1.

## Custom tiles

The converter will just copy custom tiles as-is. However, it needs to know what collision type to use for each tile, so if you add any custom tiles you need to let it know. Near the beginning of `tools/makestage.php` you'll find an array called `$colltypes`; this array contains the collision type for each tile. The types are:

* **`$00`:** no collision
* **`$01`:** solid (e.g. wall)
* **`$02`:** one-way floor
* **`$03`:** underwater
* **`$FE`:** slope (downhill to the left)
* **`$FF`:** slope (downhill to the right)

You also will want to add the tile to the pattern table so you can see it in Tiled. To do this, create a PNG with the tile with the respective number as filename in `src-data/tileset` (make sure the hex letters are in uppercase!), then call `tools/tileset.php`. Tiled should reload the pattern table immediately even if the stage is already opened :)

Finally you need to let the game know. The tile patterns are in `data/tileset.68k`. If you need new graphics then you'll need to modify `src-data/tileset/gfxbuild` accordingly and then update `VramTileset` in both `src-68k/variables.68k` and `src-68k/ingame.68k`.

## Slopes

Internally, slopes are actually platform objects (because handling them through map collision never worked right). However, the level editor will detect slope tiles and automatically generate the relevant objects for them.

Note that this means that you need to take them into account towards the object count.

## Special features

The converter will detect special features in the stages depending on what it finds inside. Note that in many cases these are usually *not* compatible with each other, and results will be impredictable if you attempt so:

* If there's a water tile, it'll enable the underwater raster effect and load the underwater palettes. Note that the surface of the water must be always at the same height through all the room.

* If there's one of the bluish tiles used when going in the walls outside the castle, it'll enable the fog parallax and load the fog palettes.

* If there are bookshelf tiles (`$37` to `$3F`), it'll load the bookshelf palette. (those books need more colors than normally available mmkay?)

* The levels with the `lava` filenames will load the lava graphics and palettes.

    * Levels with `lava_2` and `lava_3` in their filenames will load parallax code for the rising lava and flooding lava, respectively (as lava is drawn using plane B).
    
    * Meanwhile `lava_4` causes the explosion where Merlina spawns.

* The levels with the `mirror` filenames will load the mirror palettes and create the reflection object.

    * `mirror_2` will cause the reflection to be a boss. *(currently partially implemented in-game)*

To change what special features are used, you should edit the level converter (`tools/makestage.php`).
