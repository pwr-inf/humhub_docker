FROM tutum/lamp:latest
RUN apt-get update && apt-get install -y curl  php5-curl php5-ldap php5-intl php5-gd php5-sqlite 
RUN rm -fr /app && git clone https://github.com/humhub/humhub.git /app
RUN cd /app && git checkout v1.1.0 && cd -
RUN mkdir -p /root/.composer
COPY config.json /root/.composer
RUN chown -R mysql:mysql /var/lib/mysql

RUN cd /app && \
    curl -sS https://getcomposer.org/installer | php
RUN cd /app && ./composer.phar global require "fxp/composer-asset-plugin:~1.1.1"
RUN cd /app && ./composer.phar update
COPY common.php /common.php
COPY Engine.php /Engine.php
COPY finish.sh /finish.sh
COPY start.sh /start.sh
RUN chmod +x /finish.sh
RUN chmod +x /start.sh

RUN chown -R www-data:www-data /app
RUN cd /app && chmod -R +w assets protected uploads 
RUN cd /app && chmod +x protected/yii*

RUN sed -i -e "s#mysql_install_db#mysql_install_db --user=mysql#g" /run.sh
RUN sed -i -e "s#uroot#umysql#g"  /run.sh

EXPOSE 80 3306
CMD ["/start.sh"]
