FROM alpine:latest

SHELL ["/bin/sh", "-euo", "pipefail", "-c"]

RUN mkdir -p /var/log/remote && \
    apk --no-cache add rsyslog net-tools

EXPOSE 1514/udp

CMD ["rsyslogd", "-n", "-f", "/etc/rsyslog.conf"]
