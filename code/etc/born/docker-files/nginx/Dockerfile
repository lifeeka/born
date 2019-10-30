ARG NGINX_VERSION
FROM nginx:${NGINX_VERSION}
WORKDIR /app

ARG TZ
RUN echo -e "\033[0;32mTimezone set to $TZ\033[0m"
RUN apk add curl

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ARG NGINX_PORT

EXPOSE ${NGINX_PORT}
