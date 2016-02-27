# Localization

## Choosing a language

The game can be built in different languages. The ROM is hardcoded to a single language that can be selected from `buildme.68k` (make sure exactly *one* of these is set):

* **`LANG_EN`:** English
* **`LANG_ES`:** Spanish

If you want to add your own language, you should add your own language here, set it and then go chase all the assembly errors you'll inevitably get (that's the easiest way to know what's missing to localize).

## Strings

The game uses nul-terminated mostly-ASCII strings in a lot of places (e.g. menus). You can find the localizable strings in the `data/text/` directory (one file for each language). Non-localizable strings (such as the copyright notice) are in `data/text.68k` itself instead.

### Special symbols

As mentioned earlier, strings are mostly-ASCII. Besides being uppercase-only and only a few symbols being present, the following characters differ from ASCII:

* `@` (`$40`) → `©`
* `#` (`$23`) → `Ñ`

## Game logo

The name of the game itself is localizable (e.g. "El Castillo del Dragón" in Spanish), and this means the graphics for its name in the title screen need to change. This is done by having completely different graphics for each language, then having `data/title.68k` choose one when the ROM is being built.

Places you need to touch:

* The relevant mdtiler script (`src-data/title/gfxbuild`)
* The file including the graphics (`data/title.68k`)
* The build script (`build.sh`)

### mdtiler script

Open `src-data/title/gfxbuild` and you'll find blocks like these:

    input "src-data/title/logo_en.png"
    output "src-data/title/logo_en.4bpp"
    output2 "src-data/title/logo_en.map"
    map 0 0 29 11

Copypaste the block, change the filenames to your language then change the last two numbers to the size of the logo in tiles (this example is 29×11 tiles).

### Assembly file

This is the trickiest one of the bunch. Open `data/title.68k` to find stuff like this:

        if LANG_EN
    TITLELOGO_W: equ 29
    TITLELOGO_H: equ 11
    TITLELOGO_Z: equ filesize("src-data/title/logo_en.4bpp")/$20
    GfxTitleLogo:
        incbin  "data/title/logo_en.slz"
        even
        endc

Copypaste this, change the `LANG_EN` and the two filenames to match the language, and then adjust `TITLELOGO_W` and `TITLELOGO_H` to match those from the mdtiler script.

### Build script

Open `build.sh` and look for a line like this:

    cat src-data/title/logo_en.map src-data/title/logo_en.4bpp > src-data/title/logo_en.blob

Copypaste it and change the filenames to your language.
