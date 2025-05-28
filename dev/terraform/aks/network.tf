# Resource Group for common resources
resource "azurerm_resource_group" "common_rg" {
  name     = "NETWORK_RESOURCES"
  location = var.location
}

# Shared Virtual Network in the common resource group
resource "azurerm_virtual_network" "shared_vnet" {
  name                = "Shared-VNet"
  address_space       = ["10.0.0.0/16"]
  location            = azurerm_resource_group.common_rg.location
  resource_group_name = azurerm_resource_group.common_rg.name
}

# Public Subnet in the common resource group
resource "azurerm_subnet" "public_subnet" {
  name                 = "Public-Subnet"
  resource_group_name  = azurerm_resource_group.common_rg.name
  virtual_network_name = azurerm_virtual_network.shared_vnet.name
  address_prefixes     = ["10.0.1.0/24"]
}

# Private Subnet in the common resource group
resource "azurerm_subnet" "private_subnet" {
  name                 = "Private-Subnet"
  resource_group_name  = azurerm_resource_group.common_rg.name
  virtual_network_name = azurerm_virtual_network.shared_vnet.name
  address_prefixes     = ["10.0.2.0/24"]
}

# Public IP for Public VM in the common resource group
resource "azurerm_public_ip" "public_vm_ip" {
  name                = "Public-VM-PublicIP"
  location            = azurerm_resource_group.common_rg.location
  resource_group_name = azurerm_resource_group.common_rg.name
  allocation_method   = "Static"
  sku                 = "Standard"
}