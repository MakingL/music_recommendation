FROM python:3.6.8
LABEL maintainer="michallee104@126.com"

ENV LANG C.UTF-8

RUN mkdir /code
WORKDIR /code
COPY . /code

# Accelerate APT
RUN sed -i "s@http://deb.debian.org@http://mirrors.aliyun.com@g" /etc/apt/sources.list \
    &&  apt-get clean && apt-get -y update && apt-get -y install sox libsox-fmt-mp3

RUN pip install  -i https://pypi.tuna.tsinghua.edu.cn/simple --upgrade pip && pip install -i https://pypi.tuna.tsinghua.edu.cn/simple -r requirements.txt

EXPOSE 6016 6016
