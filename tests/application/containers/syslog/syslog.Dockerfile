FROM alpine:3

RUN mkdir -p /var/log/remote && \
    apk --no-cache add rsyslog net-tools

COPY rsyslog.conf /etc/rsyslog.conf

EXPOSE 1514/udp

CMD ["rsyslogd", "-n", "-f", "/etc/rsyslog.conf"]
