ARG PYTHON_VERSION
FROM python:${PYTHON_VERSION}
WORKDIR /app

ARG TZ
RUN echo -e "\033[0;32mTimezone set to $TZ\033[0m"

RUN apt update -y

RUN pip install pipenv
RUN pipenv install
RUN pipenv sync -d

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
