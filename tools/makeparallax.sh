#!/bin/sh

gcc -O3 -std=c99 -s -o tools/fog tools/fog.c -lm

tools/fog
