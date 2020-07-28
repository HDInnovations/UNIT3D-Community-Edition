FROM caddy/caddy:latest
VOLUME ["/app/public", "/app/public/files"]
COPY docker/Caddyfile /etc/caddy/Caddyfile