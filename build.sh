#!/bin/sh

GAMENAME=witch.bin

asm() { wine tools/asm68k.exe /p $1, $2, , listing.txt ; }
gfx() { ./tools/mdtiler $1 ; }
slz() { ./tools/slz $1 $2 ; }
eif() { ./tools/tfi2eif $1 $2 ; }
ewf() { ./tools/pcm2ewf $1 $2 ; }
#esf() { php ~/azurasun/tools/makeesf.php $1/*.exf $2 ; }
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
stage src-data/stages/basement_5a.tmx data/stages/basement_5a.68k
stage src-data/stages/basement_5b.tmx data/stages/basement_5b.68k
stage src-data/stages/hall_1.tmx data/stages/hall_1.68k
stage src-data/stages/hall_2.tmx data/stages/hall_2.68k
stage src-data/stages/hall_3.tmx data/stages/hall_3.68k
stage src-data/stages/hall_4.tmx data/stages/hall_4.68k
stage src-data/stages/hall_4b.tmx data/stages/hall_4b.68k
stage src-data/stages/hall_5.tmx data/stages/hall_5.68k
stage src-data/stages/hall_outside.tmx data/stages/hall_outside.68k
stage src-data/stages/elevator_1.tmx data/stages/elevator_1.68k
stage src-data/stages/elevator_2.tmx data/stages/elevator_2.68k
stage src-data/stages/elevator_3.tmx data/stages/elevator_3.68k
stage src-data/stages/elevator_4.tmx data/stages/elevator_4.68k
stage src-data/stages/library_0.tmx data/stages/library_0.68k
stage src-data/stages/library_1.tmx data/stages/library_1.68k
stage src-data/stages/library_2.tmx data/stages/library_2.68k
stage src-data/stages/library_3.tmx data/stages/library_3.68k
stage src-data/stages/prison_1.tmx data/stages/prison_1.68k
stage src-data/stages/prison_2.tmx data/stages/prison_2.68k
stage src-data/stages/prison_3.tmx data/stages/prison_3.68k
stage src-data/stages/prison_4.tmx data/stages/prison_4.68k
stage src-data/stages/prison_5.tmx data/stages/prison_5.68k
stage src-data/stages/shrine_0.tmx data/stages/shrine_0.68k
stage src-data/stages/shrine_1.tmx data/stages/shrine_1.68k
stage src-data/stages/shrine_2.tmx data/stages/shrine_2.68k
stage src-data/stages/tower_1.tmx data/stages/tower_1.68k
stage src-data/stages/tower_2.tmx data/stages/tower_2.68k
stage src-data/stages/tower_3.tmx data/stages/tower_3.68k
stage src-data/stages/tower_4.tmx data/stages/tower_4.68k
stage src-data/stages/tower_5.tmx data/stages/tower_5.68k
stage src-data/stages/lava_1.tmx data/stages/lava_1.68k
stage src-data/stages/lava_2.tmx data/stages/lava_2.68k
stage src-data/stages/lava_2b.tmx data/stages/lava_2b.68k
stage src-data/stages/lava_3.tmx data/stages/lava_3.68k
stage src-data/stages/lava_4.tmx data/stages/lava_4.68k
stage src-data/stages/wall_1.tmx data/stages/wall_1.68k
stage src-data/stages/wall_2.tmx data/stages/wall_2.68k
stage src-data/stages/wall_3.tmx data/stages/wall_3.68k
stage src-data/stages/wall_4.tmx data/stages/wall_4.68k
stage src-data/stages/wall_5.tmx data/stages/wall_5.68k
stage src-data/stages/wall_6.tmx data/stages/wall_6.68k
stage src-data/stages/rest_0.tmx data/stages/rest_0.68k
stage src-data/stages/rest_1.tmx data/stages/rest_1.68k
stage src-data/stages/rest_2.tmx data/stages/rest_2.68k
stage src-data/stages/rest_3.tmx data/stages/rest_3.68k
stage src-data/stages/rest_4.tmx data/stages/rest_4.68k
stage src-data/stages/rest_5.tmx data/stages/rest_5.68k
stage src-data/stages/rest_6.tmx data/stages/rest_6.68k
stage src-data/stages/platforms_1.tmx data/stages/platforms_1.68k
stage src-data/stages/platforms_2.tmx data/stages/platforms_2.68k
stage src-data/stages/platforms_3.tmx data/stages/platforms_3.68k
stage src-data/stages/platforms_4.tmx data/stages/platforms_4.68k
stage src-data/stages/platforms_5.tmx data/stages/platforms_5.68k
stage src-data/stages/platforms_6.tmx data/stages/platforms_6.68k
stage src-data/stages/rooftop_start.tmx data/stages/rooftop_start.68k
stage src-data/stages/rooftop_1.tmx data/stages/rooftop_1.68k
stage src-data/stages/rooftop_1b.tmx data/stages/rooftop_1b.68k
stage src-data/stages/rooftop_2.tmx data/stages/rooftop_2.68k
stage src-data/stages/rooftop_2b.tmx data/stages/rooftop_2b.68k
stage src-data/stages/rooftop_3.tmx data/stages/rooftop_3.68k
stage src-data/stages/rooftop_3b.tmx data/stages/rooftop_3b.68k
stage src-data/stages/rooftop_4.tmx data/stages/rooftop_4.68k
stage src-data/stages/rooftop_end.tmx data/stages/rooftop_end.68k
stage src-data/stages/maze_1.tmx data/stages/maze_1.68k
stage src-data/stages/maze_2.tmx data/stages/maze_2.68k
stage src-data/stages/maze_2a.tmx data/stages/maze_2a.68k
stage src-data/stages/maze_2b.tmx data/stages/maze_2b.68k
stage src-data/stages/maze_2c.tmx data/stages/maze_2c.68k
stage src-data/stages/maze_3.tmx data/stages/maze_3.68k
stage src-data/stages/maze_3a.tmx data/stages/maze_3a.68k
stage src-data/stages/maze_3b.tmx data/stages/maze_3b.68k
stage src-data/stages/maze_4.tmx data/stages/maze_4.68k
stage src-data/stages/mirror_1.tmx data/stages/mirror_1.68k
stage src-data/stages/mirror_2.tmx data/stages/mirror_2.68k
stage src-data/stages/dragon_tower_1.tmx data/stages/dragon_tower_1.68k
stage src-data/stages/dragon_tower_2.tmx data/stages/dragon_tower_2.68k
stage src-data/stages/dragon_tower_3.tmx data/stages/dragon_tower_3.68k
stage src-data/stages/dragon_tower_4.tmx data/stages/dragon_tower_4.68k
stage src-data/stages/dragon_tower_5.tmx data/stages/dragon_tower_5.68k
stage src-data/stages/dragon_tower_6.tmx data/stages/dragon_tower_6.68k
stage src-data/stages/dragon_tower_7.tmx data/stages/dragon_tower_7.68k
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
cat src-data/parallax/rooftop.map src-data/parallax/rooftop.4bpp > src-data/parallax/rooftop.blob
cat src-data/title/logo_en.map src-data/title/logo_en.4bpp > src-data/title/logo_en.blob
cat src-data/title/logo_es.map src-data/title/logo_es.4bpp > src-data/title/logo_es.blob

slz src-data/enemies/enemies.4bpp data/enemies/enemies.slz
slz src-data/enemies/cogwheel.4bpp data/enemies/cogwheel.slz
slz src-data/enemies/lava.4bpp data/enemies/lava.slz
slz src-data/tileset/tileset.4bpp data/tileset/tileset.slz
slz src-data/tileset/portrait.4bpp data/tileset/portrait.slz
slz src-data/ingame/ingame.4bpp data/ingame/ingame.slz
slz src-data/parallax/fog.blob data/parallax/fog.slz
slz src-data/parallax/rooftop.blob data/parallax/rooftop.slz
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
#esf src-data/music/ingame data/music/ingame.esf
#esf src-data/music/dragon data/music/dragon.esf

rm -f "$GAMENAME"
asm buildme.68k tmp.bin
mv tmp.bin "$GAMENAME"
