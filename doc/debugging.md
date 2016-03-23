# Debugging tools

You can enable or disable some debugging features when you build the ROM. These features can be turned on or off by modifying `buildme.68k`.

* **`DEBUG_INIT`:** fills VRAM with garbage at power on.
* **`DEBUG_MENU`:** enables the debug menu before starting the game.
* **`DEBUG_CONTROLS`:** enables debug-only controls during pause.
* **`DEBUG_HUD`:** shows debug information in the HUD.

## Debug menu

This menu shows up before you start playing and lets you tweak several values:

* **Starting stage:** lets you choose which room you want to start at.
* **Invincibility:** makes you invincible against enemies (you can still die by falling in pits).
* **Infinite lives:** keeps your lives counter permanently maxed out.
* **Sound test:** same as in Options menu.

Note that the starting stage is currently *not* capped, so make sure to not select a value that isn't a valid room! (or the game will most likely crash) You can quickly increase or decrease the stage by holding A (this will make the D-pad to move in steps of 10 stages).

## Debug controls

While the game is paused, you can press the following buttons:

* **↑ button:** Choose next stage (see top right of screen)
* **↓ button:** Choose previous stage (see top right of screen)
* **A button:** Go to the chosen stage
* **B button:** Toogle free move

To change to another stage, choose it using the D-pad, then press A (if you *don't* press A then the game won't switch stages!). Free move is enabled by pressing the B button, then unpause the game and use the D-pad to move around (press B while paused again to disable free move).

Note that if you want to reset the game (A+B+C while paused), make sure that the A button is the last one that gets pressed, or the game will restart the stage instead!

## Debug HUD

When this HUD is enabled, you'll see two more values below the score. The first one (with a percent sign) indicates the amount of CPU usage, though this value is correct only as long as it stays within a single frame. The second number (with a cross) indicates how many objects are in the current room.
