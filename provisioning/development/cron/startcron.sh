#!/usr/bin/env bash

# cat /var/spool/cron/crontabs/root
#https://gitlab.mehrkanal.com/mehrkanal/dashboard/-/blob/develop/provisioning/development/cron/startcron.sh
#https://stackoverflow.com/questions/4880290/how-do-i-create-a-crontab-through-a-script
#https://www.cyberciti.biz/faq/linux-show-what-cron-jobs-are-setup
#https://forum.ubuntuusers.de/topic/nach-cron-neuinstallation-funktioniert-cron-ni/

echo "\
    * * * * * cd /app && /usr/local/bin/php bin/cli.php cron:execute > /cron_output
    * * * * * date > /cron_last_exec\
" >/tmp/crontab

crontab /tmp/crontab
cron -f
