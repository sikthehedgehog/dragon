# Known bugs

Should be pushing these (save the "not bugs" ones) into issues...

## Legalese

* Obviously the need to settle on a proper license.

    * Harder than it sounds. GPL could potentially backfire if it's homebrew that ends up on a cartridge, because how do you make it easy for people to get the source code? I still want it GPL-compatible though just in case somebody wants to go with GPL.

* Right now the game relies on asm68k which is a proprietary assembler, we need to either find or make a free compatible replacement. The problem is that nearly all assemblers use their own syntax for anything beyond the basics...

    * As far as I know, asmx should be mostly (if not fully) compatible (a few directives may need to be replaced). The problem is that last I checked asmx was notoriously buggy and can end up generating an incorrect binary!

## Broken stuff

* There's an annoying tendency to get glued against a wall when your back is on it and you're falling (this happens a lot if you turn around close to the wall, thanks to inertia). Given how often this throws you against a hazard, this one is worth fixing.

* The hitbox for the magic dust is ridiculous, you can even hit enemies *from behind you*. Needs to be seriously fixed.

    * Even further, it seems knights are practically immune to magic if they're facing away from you? (OK, more like you need to be extremely close to them, not a problem when they face towards you)

* Jumping off a slope makes Merlina go down a few pixels first. More often than not you can't even *tell* this, but I wonder if it'll ever backfire elsewhere.

* Sometimes Merlina snaps to the ceiling when jumping into it. While again usually not noticeable, it implies some collision code is using the wrong values somewhere.

* In the lava flood room, jumping against the ceiling causes Merlina to change the speed at which she runs away. Huh?

    * May be because of how the speed cap works? Maybe I should look into it again.

## Performance

Not broken stuff per-se, but things that hurt in terms of performance.

* Sprite drawing routines are taking up a lot of time (a huge chunk of CPU time is spent in drawing objects o_O), this brought up problems in the vertical room with many platforms and the cogwheels as CPU time skyrocketed to up to 94% and I had to make the relevant objects explicitly check for screen boundaries.

    * The problem is not `SortSprites` (that takes up a few scanlines), the bulk of the time is spent while `DrawAllObjects` (and whatever it calls) is running. You can even check this by enabling `DEBUG_DRAWUSAGE` from `buildme.68k` (this is only usable from some emulators and not real hardware though, beware!).

    * Removing the boundary checks from `AddSprite` is not that useful because that'd only move the check to the objects themselves instead.

    * Probably could make boundary checks for large objects faster by getting `AddMetasprite` do it and skipping the checks in `AddSprite`, but that'd require changing the format to be fast and there would be a lot of mappings to update.

## Not bugs

Stuff that may seem like bugs but aren't (usually hacks). Also stuff that's broken simply because it isn't done yet.

* When `DEBUG_CONTROLS` is on, you have to press A last when doing the A+B+C sequence during pause. This is not a problem for a release build (where this would be disabled) and it's the only way to allow both inputs to work simultaneously.

* Often you may see garbage colors in the palette. This happens when the game doesn't need all the colors so it just lets the code load beyond what's actually used. When it doesn't use a palette row at all it just loads data from `$000000` (which is the 68000 vectors!). You won't see these garbage colors on screen, only in a debugger.
