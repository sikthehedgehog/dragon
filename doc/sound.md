# Sound in Dragon's Castle

## How to play sounds

This section describes how to make your code play sounds. Note that playing sounds doesn't take effect until the next frame starts (when the sound engine status is updated).

### Playing sound effects

To play a sound effect, you need to call `PlaySFX`, passing the sound effect ID in d7 (the list of sound effects is at the bottom). No other registers are changed, and note that the sound effect will only play if a higher priority one isn't playing.

        moveq   #SFX_JUMP, d7
        jsr     (PlaySFX).w

Sound effect priority is determined by the ID value (higher IDs take priority). Make sure that more important sound effects get higher ID values.

## Sound effect list

These are defined in `data/sound.68k`:

* **`SFX_JUMP`:** Merlina jumps
* **`SFX_SWIM`:** Merlina swims (jumps underwater)
* **`SFX_ATTACK`:** Merlina attacks (swings her broom)
* **`SFX_MAGIC`:** Merlina throws magic dust
* **`SFX_TRANSFORM`:** something transforms due to magic
* **`SFX_HIT`:** something got hit by the broom
* **`SFX_HURT`:** Merlina got hit by an enemy or hazard
* **`SFX_DEMOLISH`:** those explosions that break walls
* **`SFX_HEALTH`:** Merlina gets a red potion (health refill)
* **`SFX_SUPERMAGIC`:** Merlina gets a yellow potion (supermagic)
* **`SFX_1UP`:** Merlina gets an extra life
* **`SFX_PAUSE`:** toggling pause on/off
