# Updated Ansible Inventory Template for the private VM to use password
resource "local_file" "ansible_inventory" {
  filename = "ansible/inventory.ini"
  content  = <<EOF
[public_vm]
${azurerm_public_ip.public_vm_ip.ip_address} ansible_user=aiadmin ansible_ssh_private_key_file=~/.ssh/id_rsa ansible_python_interpreter=/usr/bin/python3


[private_vm]
${azurerm_network_interface.private_vm_nic.private_ip_address} ansible_user=aiadmin ansible_ssh_private_key_file=~/.ssh/id_rsa ansible_ssh_common_args='-o ProxyJump=aiadmin@${azurerm_public_ip.public_vm_ip.ip_address} ansible_python_interpreter=/usr/bin/python3'
EOF

  depends_on = [
    azurerm_linux_virtual_machine.public_vm,
    azurerm_linux_virtual_machine.private_vm
  ]
}

resource "null_resource" "provision_with_ansible" {
  provisioner "local-exec" {
    command = <<EOT
      echo "Running Ansible playbook to provision Public and Private VMs..."
      ansible-playbook -i ${local_file.ansible_inventory.filename} private-vm.yaml --extra-vars 'mariadb_root_password=${var.admin_password}' -vvv  | tee ansible_private-vm_output.log
      ansible-playbook -i ${local_file.ansible_inventory.filename} public-vm.yaml -vvv  | tee ansible_public-vm_output.log
    EOT
  }

  depends_on = [
    local_file.ansible_inventory
  ]
}