# User imager from phalcon 3.0
FROM xuanthinh244/docker-phalcon:v3.0.1-DevLog
MAINTAINER vi.nt "<vi.nt@geekup.vn>"

# Install supervisord
RUN apt update && apt install -y supervisor;

# Copy supervisord config
COPY docker-config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create directory /var/www/html/app and set it as a mount point 
RUN mkdir -p /var/www/html
VOLUME /var/www/html
WORKDIR /var/www/html

# set cmd command
CMD ["/usr/bin/supervisord"]