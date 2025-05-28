ssh -N -L 2223:192.168.1.4:22 aiadmin@localhost
ssh -N -L 2222:192.168.1.4:22 aiadmin@localhost


ssh-keygen -t rsa -b 4096 -f ~/.ssh/prodigies.ai
ssh-copy-id -i ~/.ssh/frontend_key.pub obarrientos@52.184.155.149

ssh-keygen -t rsa -b 4096 -f ~/.ssh/backend_key
ssh-copy-id -i ~/.ssh/backend_key.pub aiadmin@192.168.1.4


ssh -i ~/.ssh/frontend_key user@frontend-vm-ip

sudo nano /etc/nginx/nginx.conf

http {
    upstream app1 {
        server 127.0.0.1:8081;  # Puerto donde corre el contenedor de la app1
    }

    upstream app2 {
        server 127.0.0.1:8082;  # Puerto donde corre el contenedor de la app2
    }

    server {
        listen 80;
        server_name app1.example.com;

        location / {
            proxy_pass http://app1;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
        }
    }

    server {
        listen 80;
        server_name app2.example.com;

        location / {
            proxy_pass http://app2;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
        }
    }
}
