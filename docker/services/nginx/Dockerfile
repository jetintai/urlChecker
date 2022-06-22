FROM jwilder/nginx-proxy

RUN apt-get update
RUN apt-get install -y
RUN apt-get install vim -y
RUN apt-get install openssl -y

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

CMD ["nginx", "-g", "daemon off;"]
