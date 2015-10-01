<?php
   $width = 10;
   $height = 7;
   $tilemap = Array();
   $collmap = Array();
   $startx = 0x10;
   $starty = 0x10;
   
   $water = -1;
   
   $colltypes = Array(0x00, 0x01, 0x01, 0x01, 0x01, 0x02, 0x00, 0x00,
                      0x00, 0x00, 0xFF, 0x00, 0xFE, 0x00, 0x00, 0x00,
                      0x03, 0x01, 0x00, 0x00, 0x00, 0x00, 0x02, 0x00,
                      0x00, 0x00, 0x00);
   
   $objlist = "";
   $objnames = Array("OBJTYPE_GHOST",
                     "OBJTYPE_DOOR",
                     "<start>",
                     "OBJTYPE_SPIDER",
                     "OBJTYPE_SPIKEBALL");
   
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
            
            if ($water == -1 && $colltypes[$id] == 0x03) {
               $water = $y;
            }
            
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
               
            default:
               $flags = "\$00";
               break;
         }
         
         if (!$skip) {
            $objlist = $objlist."    dc.w    ".
               $name."|".$flags."<<8, ".
               sprintf("\$%04X", $x).
               ", ".sprintf("\$%04X", $y)."\n";
         }
      }
   }
   
   
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
      $out = $out."    dc.l    PalMerlina\n";
      $out = $out."    dc.l    PalTileset\n";
      $out = $out."    dc.l    PalMerlinaUnderwater\n";
      $out = $out."    dc.l    PalTilesetUnderwater\n";
   } else {
      $out = $out."    dc.l    PalMerlina\n";
      $out = $out."    dc.l    PalTileset\n";
      $out = $out."    dc.l    0\n";
      $out = $out."    dc.l    0\n";
   }
   
   $out = $out."    dc.w    ".sprintf("\$%04X",
      $water == -1 ? 0x7FFF : $water << 5)."\n";
   
   $out = $out."\n".$objlist."    dc.w    \$0000\n";
   
   file_put_contents($argv[2], $out);
?>
