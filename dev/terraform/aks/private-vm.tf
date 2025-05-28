# Resource Group for Private VM (BackOffice)
resource "azurerm_resource_group" "backoffice_rg" {
  name     = "BACKOFFICE_RESOURCES"
  location = var.location
}

# Private VM Network Interface in BackOffice Resource Group
resource "azurerm_network_interface" "private_vm_nic" {
  name                = "Private-VM-NIC"
  location            = azurerm_resource_group.backoffice_rg.location
  resource_group_name = azurerm_resource_group.backoffice_rg.name

  ip_configuration {
    name                          = "Private-VM-IPConfig"
    subnet_id                     = azurerm_subnet.private_subnet.id
    private_ip_address_allocation = "Dynamic"
  }
}

# Associate NSG with Private VM NIC
resource "azurerm_network_interface_security_group_association" "private_nic_nsg_assoc" {
  network_interface_id      = azurerm_network_interface.private_vm_nic.id
  network_security_group_id = azurerm_network_security_group.private_nsg.id
}

# Create Private VM BackOffice 
resource "azurerm_linux_virtual_machine" "private_vm" {
  name                = "BackOffice-VM"
  resource_group_name = azurerm_resource_group.backoffice_rg.name
  location            = azurerm_resource_group.backoffice_rg.location
  size                = var.vm_size
  admin_username      = var.admin_username

  admin_ssh_key {
    username   = var.admin_username
    public_key = file("~/.ssh/id_rsa.pub")
  }

  network_interface_ids = [
    azurerm_network_interface.private_vm_nic.id
  ]

  os_disk {
    name              = "BackOffice-VM-Disk"
    caching           = "ReadWrite"
    storage_account_type = "Standard_LRS"
    disk_size_gb      = 30
  }

  source_image_reference {
    publisher = "Canonical"
    offer     = "UbuntuServer"
    sku       = "18.04-LTS"
    version   = "latest"
  }
}

