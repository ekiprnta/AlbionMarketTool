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
rcli update:raw  # update raw 
rcli update:resource  # update resource 
rcli update:journal  # update journal
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

* Tier überarbeiten
* Farming
* Javascript überall
* No Spec Crafting
* Stone