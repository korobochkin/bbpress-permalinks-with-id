FROM alpine:latest

SHELL ["/bin/sh", "-euo", "pipefail", "-c"]

RUN \
    --mount=type=cache,target=/var/cache/apk,sharing=locked \
    apk update --progress=no \
    && apk upgrade --progress=no \
    && apk add --progress=no rsyslog net-tools \
    && mkdir -p /var/log/remote

ARG UID
ARG GID

RUN \
   addgroup -g $GID syslog \
   && adduser -u $UID -G syslog -D -s /bin/sh syslog

USER $UID:$GID

EXPOSE 1514/udp

CMD ["rsyslogd", "-n", "-f", "/etc/rsyslog.conf"]
