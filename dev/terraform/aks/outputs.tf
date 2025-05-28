# Output the Public IP of the Public-facing VM
output "public_vm_ip" {
  value = azurerm_public_ip.public_vm_ip.ip_address
}

# Output the Private IP of the Private VM
output "private_vm_ip" {
  value = azurerm_network_interface.private_vm_nic.private_ip_address
}