sudo tail -n 100 /var/log/cloud-init-output.log
sudo tail -n 100 /var/log/cloud-init.log

ssh-keygen -R 10.0.2.4
ssh aiadmin@104.210.0.239
ssh -J aiadmin@104.210.0.239 aiadmin@10.0.2.4


ssh-keygen -t rsa -b 4096 -f ~/.ssh/backend_key
cat ~/.ssh/backend_key.pub | ssh aiadmin@10.0.2.4 'mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys && chmod 700 ~/.ssh'



curl -sfL https://get.k3s.io | sh -

cat /var/lib/rancher/k3s/server/node-token

aiadmin@BackOffice-VM:~$ curl -sfL https://get.k3s.io | K3S_URL=https://10.0.2.4:6443 K3S_TOKEN=K1032b11c30e178843378df4544e62db53d2efdd295a206ad938d6db0d0ad62ec57::server:d7b7a836294e098a001ae44b3e8e84ee sh -
[INFO]  Finding release for channel stable

aiadmin@BackOffice-VM:~$ sudo chmod 644 /etc/rancher/k3s/k3s.yaml
aiadmin@BackOffice-VM:~$ export KUBECONFIG=/etc/rancher/k3s/k3s.yaml
aiadmin@BackOffice-VM:~$ kubectl get nodes