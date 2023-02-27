FROM registry.mehrkanal.com/docker/phpx/fpm:8.1-dev
RUN apt-get update && apt-get install cron libmemcached-dev libz-dev -y && apt-get clean && \
    apt-get autoclean && \
    apt-get autoremove && \
    rm -rf /var/cache/apt/* && \
    rm -rf /var/lib/apt/lists/*

COPY startcron.sh /
RUN chmod +x /startchron.sh
CMD /startcron.sh
