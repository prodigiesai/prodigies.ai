scp aiadmin@52.167.138.135:/etc/rancher/k3s/k3s.yaml ~/k3s-vm1.yaml
scp aiadmin@52.184.155.149:/etc/rancher/k3s/k3s.yaml ~/k3s-vm2.yaml

sudo chmod 644 /etc/rancher/k3s/k3s.yaml
export KUBECONFIG=/etc/rancher/k3s/k3s.yaml
k3s kubectl get nodes



# For VM1, update the server field in k3s-vm1.yaml:

yaml
Copy code
server: https://vm1-ip:6443


# For VM2, update the server field in k3s-vm2.yaml:
yaml
Copy code
server: https://vm2-ip:6443


export KUBECONFIG=~/k3s-vm1.yaml
export KUBECONFIG=~/k3s-vm2.yaml


KUBECONFIG=~/k3s-vm1.yaml:~/k3s-vm2.yaml kubectl config view --flatten > ~/.kube/config


scp -i ~/.ssh/id_rsa aiadmin@104.210.0.239:/etc/rancher/k3s/k3s.yaml ~/kubecfg_prodigiesai

