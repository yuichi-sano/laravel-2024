#!/bin/bash

# start supervisor
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

# add AWS_CONTAINER_CREDENTIALS_RELATIVE_URI
echo "AWS_CONTAINER_CREDENTIALS_RELATIVE_URI=$(strings /proc/1/environ | grep AWS_CONTAINER_CREDENTIALS_RELATIVE_URI | cut -d = -f2 )" >> /var/www/html/.env.stg

# update env form ssm
echo "DB_DATABASE=$LARAVEL_DATABASE_DATABASE" >> /var/www/html/.env.stg
echo "DB_USERNAME=$LARAVEL_DATABASE_USERNAME" >> /var/www/html/.env.stg
echo "DB_HOST=$LARAVEL_DATABASE_HOST" >> /var/www/html/.env.stg
echo "DB_PASSWORD=$LARAVEL_DATABASE_PASSWORD" >> /var/www/html/.env.stg
echo "REDIS_HOST=$LARAVEL_REDIS_HOST" >> /var/www/html/.env.stg
echo "REDIS_PASSWORD=$LARAVEL_REDIS_PASSWORD" >> /var/www/html/.env.stg
echo "MAIL_PASSWORD=$LARAVEL_SENDGRID_API_KEY" >> /var/www/html/.env.stg
echo "MAIL_WEBHOOK_KEY=$LARAVEL_SENDGRID_WEBHOOK_KEY" >> /var/www/html/.env.stg
echo "MAIL_FROM_ADDRESS=$LARAVEL_SENDER" >> /var/www/html/.env.stg
echo "ZOOM_CLIENT_ID=$LARAVEL_ZOOM_CLIENT_ID" >> /var/www/html/.env.stg
echo "ZOOM_CLIENT_SECRET=$LARAVEL_ZOOM_CLIENT_SECRET" >> /var/www/html/.env.stg
echo "ZOOM_VERIFICATION_TOKEN=$LARAVEL_ZOOM_VERIFICATION_TOKEN" >> /var/www/html/.env.stg
echo "ZOOM_SECRET_TOKEN=$LARAVEL_ZOOM_SECRET_TOKEN" >> /var/www/html/.env.stg
echo "TEAMS_CLIENT_ID=$LARAVEL_TEAMS_CLIENT_ID" >> /var/www/html/.env.stg
echo "TEAMS_CLIENT_SECRET=$LARAVEL_TEAMS_CLIENT_SECRET" >> /var/www/html/.env.stg

/bin/bash
