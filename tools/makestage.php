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
   
   $anim = 0x0000;
   $water = -1;
   $cogwheel = false;
   $outdoors = false;
   $mirror = (strstr($argv[1], "mirror") !== false);
   $lava = (strstr($argv[1], "lava") !== false);
   
   $colltypes = Array(0x00, 0x01, 0x01, 0x01, 0x01, 0x02, 0x00, 0x00,
                      0x00, 0x00, 0xFF, 0x00, 0xFE, 0x00, 0x00, 0x00,
                      0x03, 0x01, 0x00, 0x00, 0x00, 0x00, 0x02, 0x00,
                      0x00, 0x00, 0x00, 0x00, 0x00, 0x01, 0x01, 0x01,
                      0x00, 0x02, 0x00, 0x00, 0x00, 0x00, 0x00, 0x02,
                      0x02, 0x02, 0x02);
   
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
                     "OBJTYPE_HSWINGBALL");
   
   $in = explode("\n", file_get_contents($argv[1]));
   
   $inmap = FALSE;
   $inobj = FALSE;
   foreach ($in as &$line) {
      $line = trim($line);
      if (substr($line, 0, 4) == "<map") {
         $width = (int)(substr(strstr($line, "width=\""), 7));
         $height = (int)(substr(strstr($line, "height=\""), 8));
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
            
            $id = (int)($id) - 1;
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
            
            if ($water == -1 && $colltypes[$id] == 0x03)
               $water = $y;
            if ($id == 0x20 || $id == 0x21)
               $outdoors = true;
            
            $x++;
         }
         
         $y++;
      }
      if ($inobj) {
         $type = (int)(substr(strstr($line, "gid=\""), 5));
         $x = (int)(substr(strstr($line, "x=\""), 3));
         $y = (int)(substr(strstr($line, "y=\""), 3));
         
         $name = $objnames[$type - 65];
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
   
   if ($argv[1] == (strstr($argv[1], "mirror_1") !== false))
      $objlist = $objlist."    dc.w    OBJTYPE_REFLECTION|0<<8, 0, 0\n";
   if ($argv[1] == (strstr($argv[1], "mirror_2") !== false))
      $objlist = $objlist."    dc.w    OBJTYPE_REFLECTION|1<<8, 0, 0\n";
   
   
   $out = "";
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
   }
   if ($lava) {
      $pal[2] = "PalLava";
      $pal[3] = "PalFrozenLava";
      $parallax = "LavaParallax";
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
