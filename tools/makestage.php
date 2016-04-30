<?php
   $width = 10;
   $height = 7;
   $tilemap = Array();
   $collmap = Array();
   $pal = Array("PalMerlina", "PalTileset", "0", "0");
   $parallax = "NoParallax";
   $extragfx = "0";
   $extrasize = 0;
   $startx = 0x10;
   $starty = 0x10;
   
   $tid = 0x01;
   $oid = 0x41;
   
   $anim = 0x0000;
   $water = -1;
   $cogwheel = false;
   $outdoors = false;
   $mirror = (strstr($argv[1], "mirror") !== false);
   $lava = (strstr($argv[1], "lava") !== false);
   $bookshelf = false;
   $wall = (strstr($argv[1], "wall") !== false);
   $portraits = false;
   $rooftop = false;
   
   $colltypes = Array(0x00, 0x01, 0x01, 0x01, 0x01, 0x02, 0x00, 0x00,
                      0x00, 0x00, 0xFF, 0x00, 0xFE, 0x00, 0x00, 0x00,
                      0x03, 0x01, 0x00, 0x00, 0x00, 0x00, 0x02, 0x00,
                      0x00, 0x00, 0x00, 0x00, 0x00, 0x01, 0x01, 0x01,
                      0x00, 0x02, 0x00, 0x00, 0x00, 0x00, 0x00, 0x02,
                      0x02, 0x02, 0x02, 0x01, 0x03, 0x03, 0x03, 0x03,
                      0x00, 0x00, 0x00, 0x00, 0x01, 0x01, 0x01, 0x00,
                      0x02, 0x00, 0x00, 0x02, 0x00, 0x00, 0x02, 0x00,
                      0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
                      0xFF, 0x00, 0xFE, 0x00, 0x00, 0x00, 0x00, 0x00,
                      0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
                      0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00);
   
   $objlist = "";
   $objnames = Array("OBJTYPE_GHOST",
                     "OBJTYPE_DOOR",
                     "<start>",
                     "OBJTYPE_SPIDER",
                     "OBJTYPE_SPIKEBALL",
                     "OBJTYPE_DPLATFORM",
                     "OBJTYPE_UPLATFORM",
                     "OBJTYPE_HCHAIN",
                     "OBJTYPE_VCHAIN",
                     "OBJTYPE_RPOTION",
                     "OBJTYPE_YPOTION",
                     "OBJTYPE_CCWPLATFORM",
                     "OBJTYPE_CWPLATFORM",
                     "OBJTYPE_SWINGBALL",
                     "OBJTYPE_CROSS",
                     "OBJTYPE_LAVABURST",
                     "OBJTYPE_COGWHEEL",
                     "OBJTYPE_HSWINGBALL",
                     "OBJTYPE_END",
                     "OBJTYPE_PIRANHA",
                     "OBJTYPE_KNIGHT",
                     "OBJTYPE_KEY",
                     "OBJTYPE_LOCKEDDOOR");
   
   $in = explode("\n", file_get_contents($argv[1]));
   
   $inmap = FALSE;
   $inobj = FALSE;
   foreach ($in as &$line) {
      $line = trim($line);
      if (substr($line, 0, 4) == "<map") {
         $width = (int)(substr(strstr($line, "width=\""), 7));
         $height = (int)(substr(strstr($line, "height=\""), 8));
      }
      if (substr($line, 0, 8) == "<tileset") {
         if (strstr($line, "name=\"tileset\"") !== FALSE)
            $tid = (int)(substr(strstr($line, "firstgid=\""), 10));
         if (strstr($line, "name=\"objset\"") !== FALSE)
            $oid = (int)(substr(strstr($line, "firstgid=\""), 10));
         continue;
      }
      if ($line == "<data encoding=\"csv\">") {
         $inmap = TRUE;
         $y = 0;
         continue;
      }
      if ($line == "</data>") {
         $inmap = FALSE;
         continue;
      }
      if (substr($line, 0, 12) == "<objectgroup") {
         $inobj = TRUE;
         continue;
      }
      if ($line == "</objectgroup>") {
         $inobj = FALSE;
         continue;
      }
      if ($inmap) {
         $x = 0;
         $ids = explode(",", $line);
         foreach($ids as &$id) {
            if ($id != (string)(int)($id)) continue;
            
            $id = (int)($id) - $tid;
            $coll = $colltypes[$id];
            
            if ($id == 0x09)
               $anim |= 0x0001;
            if ($id == 0x19 || $id == 0x1A)
               $anim |= 0x0002;
            
            if ($colltypes[$id] >= 0xFE) {
               $dir = ($coll == 0xFE) ? 1 : 0;
               $coll = 0x00;
               $objx = $x << 5;
               $objy = $y << 5;
               
               $objlist = $objlist."    dc.w    ".
                  "OBJTYPE_SLOPE|".($dir ? "$100" : "$000").", ".
                  sprintf("\$%04X, \$%04X", $objx, $objy).
                  "\n";
            }
            
            array_push($tilemap, $id);
            array_push($collmap, $coll);
            
            if ($water == -1 && ($colltypes[$id] == 0x03 ||
            ($colltypes[$id] >= 0x2B && $colltypes[$id] <= 0x2F)))
               $water = $y;
            if (($id >= 0x20 && $id <= 0x21) ||
            ($id >= 0x48 && $id <= 0x4B))
               $outdoors = true;
            if ($id >= 0x37 && $id <= 0x3F)
               $bookshelf = true;
            if ($id >= 0x4C && $id <= 0x5D)
               $portraits = true;
            if (($id >= 0x0E && $id <= 0x0F) ||
            ($id >= 0x5E && $id <= 0x5F))
               $rooftop = true;
            
            $x++;
         }
         
         $y++;
      }
      if ($inobj) {
         $type = (int)(substr(strstr($line, "gid=\""), 5));
         $x = (int)(substr(strstr($line, "x=\""), 3));
         $y = (int)(substr(strstr($line, "y=\""), 3));
         
         $name = $objnames[$type - $oid];
         $x = ($x + 0x20 + 8) & 0xFFF0;
         $y = ($y - 0x20 + 8) & 0xFFF0;
         
         $skip = FALSE;
         switch ($name) {
            case "<start>":
               $startx = $x;
               $starty = $y;
               $skip = TRUE;
               break;
               
            case "OBJTYPE_DOOR":
            case "OBJTYPE_END":
            case "OBJTYPE_LOCKEDDOOR":
               $target = substr(strstr($line, "name=\""), 6);
               if ($target !== FALSE) {
                  $target = explode("\"", $target);
                  $flags = $target[0];
               } else {
                  $flags = "\$00";
               }
               break;
               
            case "OBJTYPE_SPIDER":
               $fall = (int)(substr(strstr($line, "name=\""), 6));
               $flags = "" + ($fall / 4);
               break;
            
            case "OBJTYPE_PIRANHA":
            case "OBJTYPE_KNIGHT":
               $dir = (int)(substr(strstr($line, "name=\""), 6));
               $flags = "" + $dir;
               break;
            
            case "OBJTYPE_DPLATFORM":
            case "OBJTYPE_UPLATFORM":
               $flags = $name;
               $name = "OBJTYPE_PLATSPAWN";
               break;
            
            case "OBJTYPE_CCWPLATFORM":
               $objlist = $objlist.
                  "    dc.w    ".$name."|0x00<<8, ".
                     sprintf("\$%04X", $x).", ".sprintf("\$%04X", $y)."\n".
                  "    dc.w    ".$name."|0x55<<8, ".
                     sprintf("\$%04X", $x).", ".sprintf("\$%04X", $y)."\n".
                  "    dc.w    ".$name."|0xAA<<8, ".
                     sprintf("\$%04X", $x).", ".sprintf("\$%04X", $y)."\n";
               $name = "OBJTYPE_CHAINBASE";
               $flags = "\$00";
               break;
            
            case "OBJTYPE_CWPLATFORM":
               $objlist = $objlist.
                  "    dc.w    ".$name."|0x2B<<8, ".
                     sprintf("\$%04X", $x).", ".sprintf("\$%04X", $y)."\n".
                  "    dc.w    ".$name."|0x80<<8, ".
                     sprintf("\$%04X", $x).", ".sprintf("\$%04X", $y)."\n".
                  "    dc.w    ".$name."|0xD5<<8, ".
                     sprintf("\$%04X", $x).", ".sprintf("\$%04X", $y)."\n";
               $name = "OBJTYPE_CHAINBASE";
               $flags = "\$00";
               break;
            
            case "OBJTYPE_SWINGBALL":
               $y += 8;
               $objlist = $objlist."    dc.w    ".
                  "OBJTYPE_CHAINBASE|$00<<8, ".sprintf("\$%04X", $x).", ".
                  sprintf("\$%04X", $y)."\n";
               $angle = (int)(substr(strstr($line, "name=\""), 6));
               $flags = "" + ($angle);
               break;
            
            case "OBJTYPE_HSWINGBALL":
               $y -= 8;
               $angle = (int)(substr(strstr($line, "name=\""), 6));
               $flags = "" + ($angle);
               break;
            
            case "OBJTYPE_COGWHEEL":
               $cogwheel = true;
               $dir = (int)(substr(strstr($line, "name=\""), 6));
               $flags = "" + $dir;
               break;
            
            default:
               $flags = "\$00";
               break;
         }
         
         if (!$skip) {
            $objlist = $objlist."    dc.w    ".
               $name."|".$flags."<<8, ".
               sprintf("\$%04X", $x).
               ", ".sprintf("\$%04X", $y)."\n";
            
            if ($name == "OBJTYPE_PLATSPAWN") {
               if ($flags == "OBJTYPE_DPLATFORM")
               while ($y < $height * 0x20) {
                  $objlist = $objlist."    dc.w    ".
                     "OBJTYPE_DPLATFORM|\$00<<8, ".
                     sprintf("\$%04X", $x).
                     ", ".sprintf("\$%04X", $y)."\n";
                  $y += 0x40;
               }
               
               if ($flags == "OBJTYPE_UPLATFORM")
               while ($y > 0x00) {
                  $objlist = $objlist."    dc.w    ".
                     "OBJTYPE_UPLATFORM|\$00<<8, ".
                     sprintf("\$%04X", $x).
                     ", ".sprintf("\$%04X", $y)."\n";
                  $y -= 0x40;
               }
            }
         }
      }
   }
   
   if ($argv[1] == (strstr($argv[1], "lava_2") !== false))
      $objlist = $objlist."    dc.w    OBJTYPE_RISINGLAVA|0<<8, 0, \$".
      sprintf("%04X", $height * 0x20)."+1\n";
   if ($argv[1] == (strstr($argv[1], "lava_3") !== false))
      $objlist = $objlist."    dc.w    OBJTYPE_LAVAFLOOD|0<<8, -\$10, \$40\n";
   if ($argv[1] == (strstr($argv[1], "mirror_1") !== false))
      $objlist = $objlist."    dc.w    OBJTYPE_REFLECTION|0<<8, 0, 0\n";
   if ($argv[1] == (strstr($argv[1], "mirror_2") !== false))
      $objlist = $objlist."    dc.w    OBJTYPE_REFLECTION|1<<8, 0, 0\n";
   if ($argv[1] == (strstr($argv[1], "lava_4") !== false))
      $objlist = $objlist."    dc.w    OBJTYPE_LAVABREAK|0<<8, ".
      sprintf("\$%04X", $startx).", ".sprintf("\$%04X", $starty)."\n";
   if ($wall)
      $objlist = $objlist."    dc.w    OBJTYPE_WALL|0<<8, 0, 0\n";
   
   $name = substr(basename($argv[1], ".tmx"), 0, 16);
   $name = strtoupper($name);
   $name = str_replace("_", " ", $name);
   $name = sprintf("%-16s", $name);
   
   $out = "    if      DEBUG_MENU\n";
   $out = $out."    dc.b    \"".$name."\"\n";
   $out = $out."    endc\n\n";
   
   $out = $out."    dc.b    ".$width.", ".$height."\n";
   
   $out = $out."\n";
   for ($y = 0; $y < $height; $y++) {
      $out = $out."    dc.b    ";
      for ($x = 0; $x < $width; $x++) {
         $i = $x + $y * $width;
         $out = $out."\$".sprintf("%02X", $tilemap[$i]);
         $out = $out.(($x == $width-1) ? "\n" : ",");
      }
   }
   
   $out = $out."\n";
   for ($y = 0; $y < $height; $y++) {
      $out = $out."    dc.b    ";
      for ($x = 0; $x < $width; $x++) {
         $i = $x + $y * $width;
         $out = $out."\$".sprintf("%02X", $collmap[$i]);
         $out = $out.(($x == $width-1) ? "\n" : ",");
      }
   }
   
   $out = $out."\n";
   $out = $out."    dc.w    ".sprintf("\$%04X", $startx).", ".
                              sprintf("\$%04X", $starty)."\n";
   $out = $out."\n";
   
   if ($water != -1) {
      $pal[2] = "PalMerlinaUnderwater";
      $pal[3] = "PalTilesetUnderwater";
   }
   if ($outdoors) {
      $pal[0] = "PalMerlinaOutside";
      $pal[1] = "PalTilesetOutside";
      $parallax = "FogParallax";
   }
   if ($cogwheel) {
      $extragfx = "GfxCogwheel";
      $extrasize = 4*4 + 3*3*9;
   }
   if ($mirror) {
      $pal[2] = "PalMerlinaMirror";
      $pal[3] = "PalDoppleganger";
   }
   if ($lava) {
      $pal[2] = "PalLava";
      $pal[3] = "PalFrozenLava";
      $extragfx = "GfxLava";
      $extrasize = 4*4 + 4*2 + 4*4 + 4*4;
      if (strstr($argv[1], "lava_2") !== false)
         $parallax = "RisingLavaParallax";
      if (strstr($argv[1], "lava_3") !== false)
         $parallax = "LavaFloodParallax";
   }
   if ($bookshelf) {
      $pal[2] = "PalBookshelf";
   }
   if ($portraits) {
      $pal[2] = "PalPortrait";
      $extragfx = "GfxPortrait";
      $extrasize = 6*8*3;
   }
   if ($wall) {
      $pal[2] = "PalTilesetAlt";
      $parallax = "WallParallax";
   }
   if ($rooftop) {
      $pal[3] = "PalRooftopBG";
      $parallax = "RooftopParallax";
   }
   
   $out = $out."    dc.l    ".$pal[0]."\n";
   $out = $out."    dc.l    ".$pal[1]."\n";
   $out = $out."    dc.l    ".$pal[2]."\n";
   $out = $out."    dc.l    ".$pal[3]."\n\n";
   
   $out = $out."    dc.l    ".$extragfx."\n";
   $out = $out."    dc.w    ".$extrasize."\n";
   
   $out = $out."    dc.l    Init".$parallax."\n";
   $out = $out."    dc.l    Update".$parallax."\n";
   
   $out = $out."    dc.w    ".sprintf("\$%04X",
      $water == -1 ? 0x7FFF : $water << 5)."\n";
   
   $out = $out."\n".$objlist."    dc.w    \$0000\n";
   
   file_put_contents($argv[2], $out);
?>
