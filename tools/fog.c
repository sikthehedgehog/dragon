#include <stdint.h>
#include <stdio.h>
#include <math.h>

#define THRESHOLD -1
#define PI 3.1415926f

float fog[0x20][0x20];
uint16_t tilemap[0x40][0x20];

int main()
{
   for (unsigned y = 0; y < 0x20; y++)
   for (unsigned x = 0; x < 0x20; x++) {
      fog[x][y] = 0;
      fog[x][y] += sin(x * PI / 0x08);
      fog[x][y] -= sin(y * PI / 0x10);
      fog[x][y] += cos(x * PI / 0x04);
      fog[x][y] -= cos(y * PI / 0x02);
      fog[x][y] += sin((x + y) * PI / 0x08);
      fog[x][y] -= cos((x - y) * PI / 0x08);
      fog[x][y] += cos((x + y) * PI / 0x10);
      fog[x][y] -= sin((x - y) * PI / 0x10);
      fog[x][y] += sin(x * PI / 0x04) * sin(y * PI / 0x04);
      fog[x][y] -= cos(x * PI / 0x08) * cos(y * PI / 0x08);
      fog[x][y] += cos(x * PI / 0x02) * sin(y * PI / 0x02);
      fog[x][y] -= sin(x * PI / 0x10) * cos(y * PI / 0x10);
      fog[x][y] += cos(sin(y * PI / 0x04) * PI);
      fog[x][y] -= sin(cos(y * PI / 0x08) * PI);
   }
   
   for (unsigned y = 0; y < 0x20; y++)
   for (unsigned x = 0; x < 0x20; x++) {
      int corners = 0;
      corners |= fog[(x+0) & 0x1F][(y+0) & 0x1F] > THRESHOLD ? 0x01 : 0x00;
      corners |= fog[(x+1) & 0x1F][(y+0) & 0x1F] > THRESHOLD ? 0x02 : 0x00;
      corners |= fog[(x+0) & 0x1F][(y+1) & 0x1F] > THRESHOLD ? 0x04 : 0x00;
      corners |= fog[(x+1) & 0x1F][(y+1) & 0x1F] > THRESHOLD ? 0x08 : 0x00;
      
      uint16_t tile[2];
      switch (corners) {
         case 0x00: tile[0] = 0x0000; tile[1] = 0x0000; break;
         case 0x01: tile[0] = 0x0000; tile[1] = 0x0000; break;
         case 0x02: tile[0] = 0x0000; tile[1] = 0x0000; break;
         case 0x03: tile[0] = 0x0002; tile[1] = 0x0005; break;
         
         case 0x04: tile[0] = 0x0000; tile[1] = 0x0000; break;
         case 0x05: tile[0] = 0x0006; tile[1] = 0x0000; break;
         case 0x06: tile[0] = 0x0000; tile[1] = 0x0000; break;
         case 0x07: tile[0] = 0x0004; tile[1] = 0x0005; break;
         
         case 0x08: tile[0] = 0x0000; tile[1] = 0x0000; break;
         case 0x09: tile[0] = 0x0000; tile[1] = 0x0000; break;
         case 0x0A: tile[0] = 0x0000; tile[1] = 0x1806; break;
         case 0x0B: tile[0] = 0x0002; tile[1] = 0x0003; break;
         
         case 0x0C: tile[0] = 0x1805; tile[1] = 0x1802; break;
         case 0x0D: tile[0] = 0x1803; tile[1] = 0x1802; break;
         case 0x0E: tile[0] = 0x1805; tile[1] = 0x1804; break;
         case 0x0F: tile[0] = 0x0001; tile[1] = 0x0001; break;
      }
      
      tilemap[x*2 + 0][y] = tile[0];
      tilemap[x*2 + 1][y] = tile[1];
   }
   
   FILE *file = fopen("src-data/parallax/fog.map", "wb");
   for (unsigned y = 0; y < 0x20; y++)
   for (unsigned x = 0; x < 0x40; x++) {
      uint8_t bytes[2] = { tilemap[x][y] >> 8, tilemap[x][y] };
      fwrite(bytes, 1, 2, file);
   }
   fclose(file);
   
   return 0;
}
