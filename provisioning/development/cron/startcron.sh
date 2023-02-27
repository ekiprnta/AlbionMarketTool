#!/usr/bin/env bash

# cat /var/spool/cron/crontabs/root

echo "\
    55 */1 * * * cd /app && /usr/local/bin/php bin/cli.php update:items > /cron_defaultItems
    55 */1 * * * cd /app && /usr/local/bin/php bin/cli.php update:materials > /cron_materials
    55 */1 * * * cd /app && /usr/local/bin/php bin/cli.php update:resources > /cron_resources
    55 */1 * * * cd /app && /usr/local/bin/php bin/cli.php update:journals > /cron_journals
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:bmCrafting > /cron_market_bmCrafting
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:bmTransport > /cron_market_bmTransport
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:enchanting > /cron_market_enchanting
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:listData > /cron_market_listData
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:noSpec > /cron_market_noSpec
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:refining > /cron_market_refining
    5 */1 * * * cd /app && /usr/local/bin/php bin/cli.php market:transmutation > /cron_market_transmutation
" >/tmp/crontab

crontab /tmp/crontab
cron -f
