# Drawing sprites

## Metasprites

Metasprites are large graphics made out of multiple sprites (if you see anything larger than 32×32, chances are it's a metasprite).

### Metasprite format

Metasprites are a list of four word entries, each entry describing a sprite:

        dc.w    -$10, -$10, 0, %1111
        dc.w    -$18, -$08, 16, %0010
        dc.w    -$1F, -$11, 19, %0010
        dc.w    $8000

Each line here is a sprite. The words are, in order: X coordinate, Y coordinate, base tile (usually starting from 0), and sprite size. If you need to set any flags (e.g. flipping) then set them in the third word, as you'd expect.

The lone `$8000` at the end indicates the end of the metasprite.

### Drawing metasprites

Drawing a metasprite is similar to drawing a sprite. You need to call `AddMetasprite` with the following arguments (d5-d7 and a4-a6 will be clobbered):

* d0 = X coordinate
* d1 = Y coordinate
* d2 = tiles and flags
* a6 = address of metasprite mapping

Silly example to give an idea:

        move.w  (PlayerX), d0       ; X coordinate
        move.w  (PlayerY), d1       ; Y coordinate
        move.w  #VramPlayer, d2     ; Base tile
        lea     (Spr_Player), a6    ; Mapping
        jsr     (AddMetasprite).w

Something to note about d2: the tile value is added to those of the individual sprites, while the flags are XOR'd instead (usually palette and priority are set to 0 in the metasprite so they can be set here instead).

Also if you set the flipping flags during this call, the whole metasprite will be flipped, not just the individual sprites on their own (really, that's what you normally would expect anyway). Flipping will happen *around the metasprite's origin*, so if the origin happens to be e.g. at the center, then the metasprite will be mirrored around that point.

## Sprite priority in-game

Normally, sprite priority is just left as-is. However, inside a level sprite priority is used to sort the sprites. Moreover, there are more priority layers than what hardware normally provides (this is all handled in software).

**To repeat: this only affects when the game is during a level and does not take effect during any other screen.**

There are three layers:

* High (ghosts, magic dust and such)
* Low (players, items, most stuff really)
* Superlow (doors, etc.)

Sprites in different layers are sorting as above, sprites in the same layer are left as-is. HUD is taken out of the equation as it always appears above everything else.

### How to set priority

The layer is determined as follows:

* Priority bit determines high or low as usual
* If size bit 5 of a low sprite is set, the sprite is pushed to superlow
* If size bit 4 of a low sprite is set, the sprite is pushed to high

(the last case is used for stuff like cogwheels that needs to appear above other sprites but below walls, it's still a bit quirky and may be replaced by its own layer in the future)

In other words, for a quick run:

* **`High:`** priority high
* **`Low:`** priority low, size left as-is
* **`Superlow:`** priority low, size OR'd with `$20`

### Using it elsewhere

This complex system exists to make up for the unpredictable order in which objects may be processed. If you want to use it elsewhere, you should call `SortSprites` (no arguments) after you've added all the sprites. Beware however that this subroutine makes the assumption that the HUD exists - if your first sprite isn't high priority, it may not do what you want!
