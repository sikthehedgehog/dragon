# Game objects

## New objects

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
