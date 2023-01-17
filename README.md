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
composer run stan5
```

## Jobs
```shell 
php bin/cli.php update:item
php bin/cli.php update:all
php bin/cli.php update:resource
php bin/cli.php update:journal
```

## Team

[private](https://confluence.mehrkanal.com/#recently-worked)

## Changelogger

```shell
changelogger new -u
changelogger release <tag>
changelogger clean
```
