# Tweaking the game

## Copyright

Look up for `StrCopyright` in the file `data/text.68k`. Remember to write "`@`" when you want to write "`©`" (e.g. "`@2016`"). The copyright notice should automatically be aligned to the bottom right of the screen.

If you want to remove the copyright notice in the title screen, go to `buildme.68k` and disable `SHOW_COPYRIGHT`.

## Player physics

You can change the constants at the beginning of `src-68k/player.68k` to modify the player physics. *Note that speeds are indicated in 1/256ths of a pixel per frame (e.g. to move 2.5 pixels per second you need to use `$280`)*

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

## Scoring

The score awarded by different actions can be found in the list of constants at the beginning of `buildme.68k`. *Note that these values are BCD, so keep the `$` prefix in the numbers!* Also note that the score is capped at 99999999 points.

* **`SCORE_BROOM`:** defeating an enemy with a normal attack
* **`SCORE_MAGIC`:** defeating an enemy with a magic attack
* **`SCORE_FREEZE`:** *(for multiplyer, currently not implemented)*
* **`SCORE_ITEM`:** collecting an item
