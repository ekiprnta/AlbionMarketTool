#!/usr/bin/env bash

# cat /var/spool/cron/crontabs/root

echo "\
    * * * * * cd /app && /usr/local/bin/php bin/cli.php cron:execute > /cron_output
    * * * * * date > /cron_last_exec\
" >/tmp/crontab

crontab /tmp/crontab
cron -f
