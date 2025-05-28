[public_vm]
{{ public_vm_ip }} ansible_user=aiadmin ansible_ssh_private_key_file=~/.ssh/id_rsa

[private_vm]
{{ private_vm_ip }} ansible_user=aiadmin ansible_ssh_private_key_file=~/.ssh/id_rsa ansible_ssh_common_args='-o ProxyJump=aiadmin@{{ public_vm_ip }}'
