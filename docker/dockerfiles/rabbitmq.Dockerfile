FROM rabbitmq:3.13-management

RUN apt-get update && \
    apt-get install -y php-amqp && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

WORKDIR /var/lib/rabbitmq

EXPOSE 15672
