#!/usr/bin/env bash

# cat /var/spool/cron/crontabs/root

echo "\
    55 */1 * * * cd /app && /usr/local/bin/php bin/cli.php update:items
    55 */1 * * * cd /app && /usr/local/bin/php bin/cli.php update:materials
    55 */1 * * * cd /app && /usr/local/bin/php bin/cli.php update:resources
    55 */1 * * * cd /app && /usr/local/bin/php bin/cli.php update:journals
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:bmCrafting
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:bmTransport
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:enchanting
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:listData
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:noSpec
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:refining
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:transmutation
    * * * * * cd /app && /usr/local/bin/php bin/cli.php debug:test
    * * * * * echo 'Crontab is working'
" >/tmp/crontab

crontab /tmp/crontab
cron -f
