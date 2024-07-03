FROM nginx:stable-alpine

# 開発環境
ARG ENVIRONMENT

# env
ENV ENVIRONMENT ${ENVIRONMENT}

ENV TZ Asia/Tokyo
RUN addgroup -S sample2024 -g 1000 && adduser -S sample2024 -G sample2024 -u 1000
COPY ./docker/nginx/${ENVIRONMENT}/default.conf /etc/nginx/nginx.conf

CMD ["nginx", "-g", "daemon off;"]