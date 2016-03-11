<?php
   $base = "src-data/tileset/";
   $img = imagecreatetruecolor(0x20 * 0x08, 0x20 * 0x10);
   
   for ($i = 0; $i < 0x80; $i++) {
      $x = $i & 0x07;
      $y = $i >> 3;
      $x <<= 5;
      $y <<= 5;
      
      $tile = @imagecreatefrompng($base."tile_".sprintf("%02X", $i).".png");
      if ($tile === FALSE) break;
      imagecopy($img, $tile, $x, $y, 0, 0, 0x20, 0x20);
      imagedestroy($tile);
   }
   
   imagepng($img, $base."tileset.png");
?>
