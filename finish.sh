#!/bin/bash
cp /common.php /app/protected/config/
cp /Engine.php /app/protected/humhub/modules/user/authclient/
cp /app/.htaccess{.dist,}
sed -i -e "s#/user/auth/login#/user/auth/external?authclient=engine#g" /app/protected/humhub/config/web.php
mysql -e "CREATE DATABASE IF NOT EXISTS \`humhub\` CHARACTER SET utf8 COLLATE utf8_general_ci;"
