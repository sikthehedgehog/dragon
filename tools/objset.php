<?php
   $img = imagecreatetruecolor(0x40 * 0x08, 0x40 * 0x08);
   
   $pos = 0;
   function add($name) {
      global $img;
      global $pos;
      
      $x = $pos & 0x07;
      $y = $pos >> 3;
      $x = ($x << 6) + 0x20;
      $y = ($y << 6) + 0x20;
      
      $pic = imagecreatefrompng($name);
      $x -= imagesx($pic) / 2;
      $y -= imagesy($pic) / 2;
      
      imagecopy($img, $pic, $x, $y, 0, 0, imagesx($pic), imagesy($pic));
      imagedestroy($pic);
      
      $pos++;
   }
   
   add("src-data/enemies/ghost_1.png");
   add("src-data/ingame/door_closed.png");
   add("src-data/merlina/idle_1.png");
   add("src-data/enemies/spider_1.png");
   add("src-data/enemies/spikeball.png");
   
   imagepng($img, "src-data/tileset/objset.png");
?>
