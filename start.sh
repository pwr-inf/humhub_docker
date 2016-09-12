#!/bin/bash
chown -R mysql:mysql /var/lib/mysql
chown -R www-data:www-data /app
cd /app && chmod -R +w assets protected uploads && cd -
/run.sh
