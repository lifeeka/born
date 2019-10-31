def get_code():
    return """
FROM node:alpine
WORKDIR /app

ENV TZ=Asia/Colombo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

EXPOSE 3000
ENTRYPOINT npm run start
    
    """