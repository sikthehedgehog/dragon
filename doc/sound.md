# Playing sounds

## How to play sounds

To play a sound effect, you need to call `PlaySFX`, passing the sound effect ID in d7 (the list of sound effects is at the bottom). No other registers are changed, and note that the sound effect will only play if a higher priority one isn't playing.

        moveq   #SFX_JUMP, d7
        jsr     (PlaySFX).w

Sound effect priority is determined by the ID value (higher IDs take priority). Make sure that more important sound effects get higher ID values.

## Adding sounds

First you need to settle on a name, e.g. `boop`. Then:

* Make the sound effect stream. Either generate the ESF file directly or use the ESF assembly macros (the files in `data/sfx/` use the latter method, if you want to see how it works).
* Go into `data/sfx.68k`, then look near the end for `SfxData_*` lines. Add your own line (replacing * with the name e.g. `SfxData_Boop`) and use it to include the stream (use `incbin` for an ESF file or `include` for an assembly file)
* Scroll up to the label that says `SfxList:`, then scroll down to the list of `@Entry` lines. Add your own line using the name you've chosen (e.g. `@Entry Boop`).
    * And remember that its position determines its ID value and hence its priority. Find an appropiate location based on how important is the sound effect!
* Document it here :P

After this is done you should be able to refer to the sound effect by its constant, e.g. `SFX_BOOP`.

## Sound effect list

These are defined in `data/sound.68k`:

* **`SFX_JUMP`:** Merlina jumps
* **`SFX_SWIM`:** Merlina swims (jumps underwater)
* **`SFX_ATTACK`:** Merlina attacks (swings her broom)
* **`SFX_QUAKE`:** the room is shaking (e.g. crushing wall rooms)
* **`SFX_MAGIC`:** Merlina throws magic dust
* **`SFX_TRANSFORM`:** something transforms due to magic
* **`SFX_METAL`:** hitting something metallic (e.g. knights)
* **`SFX_HIT`:** something got hit by the broom
* **`SFX_HURT`:** Merlina got hit by an enemy or hazard
* **`SFX_DEMOLISH`:** those explosions that break walls
* **`SFX_HEALTH`:** Merlina gets a red potion (health refill)
* **`SFX_SUPERMAGIC`:** Merlina gets a yellow potion (supermagic)
* **`SFX_1UP`:** Merlina gets an extra life
* **`SFX_KEY`:** Merlina gets a key
* **`SFX_PAUSE`:** toggling pause on/off
