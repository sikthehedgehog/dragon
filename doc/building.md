# Building the ROM

The build script is meant for Linux systems. Sorry, Windows users! (you *could* try running it through Cygwin, but I don't guarantee anything) The source code was tested in a sandbox to ensure that it *actually builds* (the prebuilt binary belongs to this sandboxed test).

## Required toolchain

You'll need the following:

* asm68k (or something compatible, asm68k requires Wine)
* mdtiler (<http://tools.mdscene.net/>)
* tfi2eif (<http://tools.mdscene.net/>)
* pcm2ewf (<http://tools.mdscene.net/>)
* slz (<http://tools.mdscene.net/>)
* php (usually preinstalled on Linux)

Put all the executables inside the `tools` directory (or modify `build.sh` to point to the correct paths).

The game uses Echo as its sound engine. A build of Echo is included in the source code, but if you want to replace it you can always get the newest one from <http://echo.mdscene.net/>.

## Building

Once you sorted out the above, just run `build.sh` (make sure you run it from its directory!). If everything goes well (i.e. no assembly errors or the like), you should get two files, if they don't exist then something went wrong:

* **`witch.bin`:** the ROM, ready to play
* **`listing.txt`:** generated listing (useful for debugging)
