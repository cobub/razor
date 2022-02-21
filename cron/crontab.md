配置crontab
==============

```bash
# 表示每个小时的第五分钟执行一次脚本
5 * * * * /var/www/html/razor/cron/razor_hourly_archive.sh

# 表示每天的1：00执行一次脚本
0 1 * * * /var/www/html/razor/cron/razor_daily_archive.sh

# 表示每个星期天0:30执行一次脚本
30 0 * * 0 /var/www/html/razor/cron/razor_weekly_archive.sh

# 表示每个月第一天0:30执行一次脚本
30 0 1 * * /var/www/html/razor/cron/razor_monthly_archive.sh

# 表示每天1:30执行一次脚本
30 1 * * * /var/www/html/razor/cron/razor_laterdata_archive.sh
```