# Public VM Security Group in the common resource group
resource "azurerm_network_security_group" "public_nsg" {
  name                = "Public-NSG"
  location            = azurerm_resource_group.common_rg.location
  resource_group_name = azurerm_resource_group.common_rg.name
}

# Private VM Security Group in the common resource group
resource "azurerm_network_security_group" "private_nsg" {
  name                = "Private-NSG"
  location            = azurerm_resource_group.common_rg.location
  resource_group_name = azurerm_resource_group.common_rg.name
}

# Allow traffic from Public to Private Subnet
resource "azurerm_network_security_rule" "allow_public_to_private" {
  name                        = "Allow-Public-To-Private"
  priority                    = 100
  direction                   = "Inbound"
  access                      = "Allow"
  protocol                    = "Tcp"
  source_address_prefix       = "10.0.1.0/24"
  destination_address_prefix  = "10.0.2.0/24"
  destination_port_range      = "22"
  source_port_range           = "*"
  network_security_group_name = azurerm_network_security_group.private_nsg.name
  resource_group_name         = azurerm_resource_group.common_rg.name
}

resource "azurerm_network_security_rule" "allow_ssh" {
  name                        = "Allow-SSH"
  priority                    = 100
  direction                   = "Inbound"
  access                      = "Allow"
  protocol                    = "Tcp"
  source_port_range           = "*"
  destination_port_range      = "22"
  source_address_prefix       = "*"
  destination_address_prefix  = "*"
  network_security_group_name = azurerm_network_security_group.public_nsg.name
  resource_group_name         = azurerm_resource_group.common_rg.name
}

resource "azurerm_network_security_rule" "allow_http_https" {
  name                        = "Allow-HTTP-HTTPS"
  priority                    = 110
  direction                   = "Inbound"
  access                      = "Allow"
  protocol                    = "Tcp"
  source_port_range           = "*"
  destination_port_ranges     = ["80", "443"]
  source_address_prefix       = "*"
  destination_address_prefix  = "*"
  network_security_group_name = azurerm_network_security_group.public_nsg.name
  resource_group_name         = azurerm_resource_group.common_rg.name
}