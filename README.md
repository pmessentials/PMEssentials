# PMEssentials
A collection of essential PMMP commands, features and more. Heavily inspired by Essentials
[![HitCount](http://hits.dwyl.io/pmessentials/PMEssentials.svg)](http://hits.dwyl.io/pmessentials/PMEssentials)

This plugin works with [**AutoCompleteAPI**](https://github.com/AndreasHGK/AutoComplete), but it is not required to have this.

## Commands:
List of commands:
- [x] /back
- [x] /break
- [x] /broadcast
- [x] /burn
- [x] /clearinventory
- [x] /extinguish
- [x] /feed  
- [x] /gamemode *(custom)*
- [x] /gma
- [x] /gmc
- [x] /gms
- [x] /gmv
- [x] /god
- [x] /heal
- [x] /i
- [x] /milk
- [x] /mute
- [x] /nick
- [x] /nuke
- [x] /ping
- [x] /powertool
- [x] /realname
- [x] /size
- [x] /smite
- [x] /speed
- [x] /thru
- [x] /tpa
- [x] /tpaccept
- [x] /tpahere
- [x] /tpdeny
- [x] /tphere
- [x] /tree
- [x] /usage
- [x] /vanish
- [x] /xyz
- [ ] /afk
- [ ] /block
- [ ] /commandspy
- [ ] /delhome
- [ ] /delwarp
- [ ] /home
- [ ] /kick
- [ ] /kickall
- [ ] /reply
- [ ] /server
- [ ] /sethome
- [ ] /setspawn
- [ ] /setwarp
- [ ] /spawn
- [ ] /sudo
- [ ] /top
- [ ] /tp *(custom)*
- [ ] /tpo
- [ ] /tpohere
- [ ] /warp
- [ ] /warps
- [ ] *a ton more...*

## API:
**PMEssentials** has an API for developers can tweak a lot of features and improve them. You can access the UserMap to get users and externally enable things like godmode or vanish. The plugin also has quite a few events you can use to for instance modify command behaviour.

If you want to get the API instance, all you need to do is this:
```php
$api = API::getAPI();
```
You can get the main file too, but you probably won't need it.

Now, let's say you're creating a command and you want it to work on vanished platers. You can use these functions:
```php
//get ALL vanished players
$vplayers = $api->getVanishedPlayers();

//get vanished player from name
$vplayer = $api->getVanishedPlayer("myplayer");
```
If you want something changed in the API you can always open an issue with the API request template.
