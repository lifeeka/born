FROM node:alpine
WORKDIR /usr/lifeeka-location

ENV TZ=Asia/Colombo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

EXPOSE 3000
ENTRYPOINT npm run start


