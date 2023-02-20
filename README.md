# AlbionMarketTool

Ziel: Eint Tool zur Marktanalyse von Albion Online

## Start
```shell
eval `ssh-agent`
. ./alias.sh
dc up -d
```

[Access AlbionMarketTool](http://localhost:8080)
[Access DB](http://localhost:8081)

## Composer pakete installieren

```shell
composer up

```

## Jobs

```shell script
rcli update:items  # update items 
rcli update:resources  # update resource 
rcli update:journals  # update journal
rcli update:materials  # update materials
```

## Composer

```shell script
rcomposer up 
rcomposer ecs # ecs-fix
rcomposer stan
rcomposer rector
rcomposer phpunit
```

## Team

[private](https://confluence.mehrkanal.com/#recently-worked)

## Changelogger

```shell
changelogger new -u
changelogger release <tag>
changelogger clean
```

## ToDO

* ~~Tier überarbeiten~~
* Farming
* ~~Revisit Profit Quotient -> Profit Percentage~~
* Javascript überall
* No Spec Crafting
  * ~~Capes (028)~~
  * ~~Royal Items~~
  * ~~Enchanting (and/or BM)~~
* Stone
* Bags BM Crafting / Capes
* Rework That there is only One Item with x- Prices
* Add royal sigil enchanting
* add Artifact crafting
* Bug: Age is not correct
* Bug: Refining might not be correct

## Plans

+ make dir for Git stuff
+ add Git Entities Repository
+ improve the Frontend with Jquery