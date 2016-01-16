<?php
   function calc_usage($line, $numlines) {
      return (int)($line * 100 / $numlines);
   }
   
   function calc_all($filename, $label, $numlines) {
      $minline = $numlines - 224;
      
      $out = $label.":\n";
      for ($i = 0; $i < 224; $i++) {
         $usage = calc_usage($i + $minline, $numlines);
         $out = $out."    dc.b    \$".sprintf("%02d", $usage)."\n";
      }
      
      file_put_contents($filename, $out);
   }
   
   calc_all("data/math/ntsc_usage.68k", "NTSCUsage", 262);
   calc_all("data/math/pal_usage.68k", "PALUsage", 363);
?>
