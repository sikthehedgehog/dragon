#!/bin/sh

GAMENAME=witch.bin

asm() { wine tools/asm68k.exe /p $1, $2, , listing.txt ; }
gfx() { ./tools/mdtiler $1 ; }
slz() { ./tools/slz $1 $2 ; }
eif() { ./tools/tfi2eif $1 $2 ; }
ewf() { ./tools/pcm2ewf $1 $2 ; }
stage() { php tools/makestage.php $1 $2 ; }

stage src-data/stages/entrance_1.tmx data/stages/entrance_1.68k
stage src-data/stages/entrance_2.tmx data/stages/entrance_2.68k
stage src-data/stages/entrance_3.tmx data/stages/entrance_3.68k
stage src-data/stages/entrance_4.tmx data/stages/entrance_4.68k
stage src-data/stages/basement_1.tmx data/stages/basement_1.68k
stage src-data/stages/basement_2.tmx data/stages/basement_2.68k
stage src-data/stages/basement_3.tmx data/stages/basement_3.68k
stage src-data/stages/basement_4.tmx data/stages/basement_4.68k
stage src-data/stages/basement_5.tmx data/stages/basement_5.68k
stage src-data/stages/basement_5b.tmx data/stages/basement_5b.68k
stage src-data/stages/hall_1.tmx data/stages/hall_1.68k
stage src-data/stages/hall_2.tmx data/stages/hall_2.68k
stage src-data/stages/hall_3.tmx data/stages/hall_3.68k
stage src-data/stages/hall_4.tmx data/stages/hall_4.68k
stage src-data/stages/hall_4b.tmx data/stages/hall_4b.68k
stage src-data/stages/hall_5.tmx data/stages/hall_5.68k
stage src-data/stages/hall_outside.tmx data/stages/hall_outside.68k
stage src-data/stages/mirror_1.tmx data/stages/mirror_1.68k
stage src-data/stages/mirror_2.tmx data/stages/mirror_2.68k
stage src-data/stages/lava_1.tmx data/stages/lava_1.68k
stage src-data/stages/lava_2.tmx data/stages/lava_2.68k
stage src-data/stages/lava_3.tmx data/stages/lava_3.68k
stage src-data/stages/lava_4.tmx data/stages/lava_4.68k
stage src-data/stages/shrine_1.tmx data/stages/shrine_1.68k
stage src-data/stages/shrine_2.tmx data/stages/shrine_2.68k
stage src-data/stages/shrine_3.tmx data/stages/shrine_3.68k
stage src-data/stages/final_boss.tmx data/stages/final_boss.68k

gfx src-data/merlina/gfxbuild
gfx src-data/enemies/gfxbuild
gfx src-data/tileset/gfxbuild
gfx src-data/ingame/gfxbuild
gfx src-data/parallax/gfxbuild
gfx src-data/title/gfxbuild
gfx src-data/menu/gfxbuild
gfx src-data/error/gfxbuild

cat src-data/parallax/fog.map src-data/parallax/fog.4bpp > src-data/parallax/fog.blob
cat src-data/title/logo_en.map src-data/title/logo_en.4bpp > src-data/title/logo_en.blob
cat src-data/title/logo_es.map src-data/title/logo_es.4bpp > src-data/title/logo_es.blob

slz src-data/enemies/enemies.4bpp data/enemies/enemies.slz
slz src-data/enemies/cogwheel.4bpp data/enemies/cogwheel.slz
slz src-data/enemies/lava.4bpp data/enemies/lava.slz
slz src-data/tileset/tileset.4bpp data/tileset/tileset.slz
slz src-data/ingame/ingame.4bpp data/ingame/ingame.slz
slz src-data/parallax/fog.blob data/parallax/fog.slz
slz src-data/title/logo_en.blob data/title/logo_en.slz
slz src-data/title/logo_es.blob data/title/logo_es.slz
slz src-data/menu/font.4bpp data/menu/font.slz

eif src-data/sound/sine.tfi data/sound/sine.eif
eif src-data/sound/sawtooth.tfi data/sound/sawtooth.eif
eif src-data/sound/square.tfi data/sound/square.eif
eif src-data/sound/church.tfi data/sound/church.eif
eif src-data/sound/dguitar.tfi data/sound/dguitar.eif
eif src-data/sound/dbass.tfi data/sound/dbass.eif
eif src-data/sound/synbass.tfi data/sound/synbass.eif
eif src-data/sound/bell.tfi data/sound/bell.eif
eif src-data/sound/brass.tfi data/sound/brass.eif
eif src-data/sound/sweet.tfi data/sound/sweet.eif
eif src-data/sound/crush.tfi data/sound/crush.eif
ewf src-data/sound/punch.pcm data/sound/punch.ewf
ewf src-data/sound/explosion.pcm data/sound/explosion.ewf

rm -f "$GAMENAME"
asm buildme.68k tmp.bin
mv tmp.bin "$GAMENAME"
