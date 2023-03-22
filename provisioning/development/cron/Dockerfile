FROM php:8.1-fpm-alpine

RUN apt-get update && apt-get install cron -y && apt-get clean && \
    apt-get autoclean && \
    apt-get autoremove && \
    rm -rf /var/cache/apt/* && \
    rm -rf /var/lib/apt/lists/*

COPY startcron.sh /
RUN chmod +x /startcron.sh
CMD /startcron.sh
