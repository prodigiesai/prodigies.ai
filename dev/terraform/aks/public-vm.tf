# Resource Group for Public VM (ProdigiesAI)
resource "azurerm_resource_group" "prodigiesai_rg" {
  name     = "PRODIGIESAI_RESOURCES"
  location = var.location
}

# Public VM Network Interface in ProdigiesAI Resource Group
resource "azurerm_network_interface" "public_vm_nic" {
  name                = "Public-VM-NIC"
  location            = azurerm_resource_group.prodigiesai_rg.location
  resource_group_name = azurerm_resource_group.prodigiesai_rg.name

  ip_configuration {
    name                          = "Public-VM-IPConfig"
    subnet_id                     = azurerm_subnet.public_subnet.id
    private_ip_address_allocation = "Dynamic"
    public_ip_address_id          = azurerm_public_ip.public_vm_ip.id
  }
}

# Associate NSG with Public VM NIC
resource "azurerm_network_interface_security_group_association" "public_nic_nsg_assoc" {
  network_interface_id      = azurerm_network_interface.public_vm_nic.id
  network_security_group_id = azurerm_network_security_group.public_nsg.id
}

# Create Public VM ProdigiesAI
resource "azurerm_linux_virtual_machine" "public_vm" {
  name                = "ProdigiesAI-VM"
  resource_group_name = azurerm_resource_group.prodigiesai_rg.name
  location            = azurerm_resource_group.prodigiesai_rg.location
  size                = var.vm_size
  admin_username      = var.admin_username

  admin_ssh_key {
    username   = var.admin_username
    public_key = file("~/.ssh/id_rsa.pub")
  }

  network_interface_ids = [
    azurerm_network_interface.public_vm_nic.id
  ]

  os_disk {
    name              = "ProdigiesAI-VM-Disk"
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

