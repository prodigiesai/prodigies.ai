provider "azurerm" {
    features {}
    subscription_id = "33f8d807-62c2-4c19-9ba0-3d3cef4f5336"
  }
  
  # Variables
  variable "location" {
    default = "EastUS2"
  }
  
  variable "admin_username" {
    default = "aiadmin"
  }
  
  variable "admin_password" {
    default = "P@ssw0rd123!"
  }
  
  variable "vm_size" {
    default = "Standard_D2s_v4"
  }
  
  # Common Resource Group for shared resources
  resource "azurerm_resource_group" "common_rg" {
    name     = "Common_Resources"
    location = var.location
  }
  
  # ProdigiesAI Resource Group
  resource "azurerm_resource_group" "prodigiesai_rg" {
    name     = "ProdigiesAI_ResourceGroup"
    location = var.location
  }
  
  # BackOffice Resource Group
  resource "azurerm_resource_group" "backoffice_rg" {
    name     = "BackOffice_ResourceGroup"
    location = var.location
  }
  
  # Shared Virtual Network
  resource "azurerm_virtual_network" "shared_vnet" {
    name                = "Shared-VNet"
    address_space       = ["10.0.0.0/16"]
    location            = azurerm_resource_group.common_rg.location
    resource_group_name = azurerm_resource_group.common_rg.name
  }
  
  # Public Subnet
  resource "azurerm_subnet" "public_subnet" {
    name                 = "Public-Subnet"
    resource_group_name  = azurerm_resource_group.common_rg.name
    virtual_network_name = azurerm_virtual_network.shared_vnet.name
    address_prefixes     = ["10.0.1.0/24"]
  }
  
  # Private Subnet
  resource "azurerm_subnet" "private_subnet" {
    name                 = "Private-Subnet"
    resource_group_name  = azurerm_resource_group.common_rg.name
    virtual_network_name = azurerm_virtual_network.shared_vnet.name
    address_prefixes     = ["10.0.2.0/24"]
  }
  
  # Public VM Security Group
  resource "azurerm_network_security_group" "public_nsg" {
    name                = "Public-NSG"
    location            = azurerm_resource_group.common_rg.location
    resource_group_name = azurerm_resource_group.common_rg.name
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
  
  # Private VM Security Group
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
    destination_port_range      = "*"
    source_port_range           = "*"
    network_security_group_name = azurerm_network_security_group.private_nsg.name
    resource_group_name         = azurerm_resource_group.common_rg.name
  }
  
  # Public IP for Public VM
  resource "azurerm_public_ip" "public_vm_ip" {
    name                = "Public-VM-PublicIP"
    location            = azurerm_resource_group.common_rg.location
    resource_group_name = azurerm_resource_group.common_rg.name
    allocation_method   = "Static"
    sku                 = "Standard"
  }
  
  # Public VM (ProdigiesAI) in its own resource group
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
  
    custom_data = base64encode(<<-EOT
    #!/bin/bash
    sudo apt-get update -y
    sudo apt-get install -y docker.io
  
    # Install K3s (lightweight Kubernetes)
    sudo curl -sfL https://get.k3s.io | sh -
  
    # Create K3s Nginx deployment YAML file
    sudo cat <<EOF > /tmp/nginx-deployment.yaml
    apiVersion: apps/v1
    kind: Deployment
    metadata:
      name: nginx-deployment
      labels:
        app: nginx
    spec:
      replicas: 1
      selector:
        matchLabels:
          app: nginx
      template:
        metadata:
          labels:
            app: nginx
        spec:
          containers:
          - name: nginx
            image: nginx
            ports:
            - containerPort: 80
    EOF
  
    # Create K3s Nginx ConfigMap for reverse proxy configuration
    sudo cat <<EOF > /tmp/nginx-config.yaml
    apiVersion: v1
    kind: ConfigMap
    metadata:
      name: nginx-config
    data:
      default.conf: |
        server {
            listen 80;
            server_name _;
  
            location / {
                proxy_pass http://localhost:80;
            }
        }
    EOF
  
    # Apply the Nginx deployment and configuration in K3s
    sudo kubectl apply -f /tmp/nginx-deployment.yaml
    sudo kubectl apply -f /tmp/nginx-config.yaml
    EOT)
  }
  
  # Private VM (BackOffice) in its own resource group
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
  
    custom_data = base64encode(<<-EOT
    #!/bin/bash
    sudo apt-get update -y
    sudo apt-get install -y docker.io
  
    # Install K3s (lightweight Kubernetes)
    sudo curl -sfL https://get.k3s.io | sh -
  
    # Create K3s MariaDB deployment YAML file
    sudo cat <<EOF > /tmp/mariadb-deployment.yaml
    apiVersion: apps/v1
    kind: Deployment
    metadata:
      name: mariadb-deployment
      labels:
        app: mariadb
    spec:
      replicas: 1
      selector:
        matchLabels:
          app: mariadb
      template:
        metadata:
          labels:
            app: mariadb
        spec:
          containers:
          - name: mariadb
            image: mariadb
            env:
            - name: MYSQL_ROOT_PASSWORD
              value: "${var.admin_password}"
            ports:
            - containerPort: 3306
    EOF
  
    # Create K3s service to expose MariaDB
    sudo cat <<EOF > /tmp/mariadb-service.yaml
    apiVersion: v1
    kind: Service
    metadata:
      name: mariadb-service
    spec:
      selector:
        app: mariadb
      ports:
      - protocol: TCP
        port: 3306
        targetPort: 3306
      clusterIP: None
    EOF
  
    # Apply the MariaDB deployment and service in K3s
    sudo kubectl apply -f /tmp/mariadb-deployment.yaml
    sudo kubectl apply -f /tmp/mariadb-service.yaml
    EOT)
  }
  
  
  # Public VM Network Interface
  resource "azurerm_network_interface" "public_vm_nic" {
    name                = "Public-VM-NIC"
    location            = azurerm_resource_group.common_rg.location
    resource_group_name = azurerm_resource_group.common_rg.name
  
    ip_configuration {
      name                          = "Public-VM-IPConfig"
      subnet_id                     = azurerm_subnet.public_subnet.id
      private_ip_address_allocation = "Dynamic"
      public_ip_address_id          = azurerm_public_ip.public_vm_ip.id
    }
  }
  
  # Private VM Network Interface
  resource "azurerm_network_interface" "private_vm_nic" {
    name                = "Private-VM-NIC"
    location            = azurerm_resource_group.common_rg.location
    resource_group_name = azurerm_resource_group.common_rg.name
  
    ip_configuration {
      name                          = "Private-VM-IPConfig"
      subnet_id                     = azurerm_subnet.private_subnet.id
      private_ip_address_allocation = "Dynamic"
    }
  }
  
  # Associate NSG with Public VM NIC
  resource "azurerm_network_interface_security_group_association" "public_nic_nsg_assoc" {
    network_interface_id      = azurerm_network_interface.public_vm_nic.id
    network_security_group_id = azurerm_network_security_group.public_nsg.id
  }
  
  # Associate NSG with Private VM NIC
  resource "azurerm_network_interface_security_group_association" "private_nic_nsg_assoc" {
    network_interface_id      = azurerm_network_interface.private_vm_nic.id
    network_security_group_id = azurerm_network_security_group.private_nsg.id
  }
  
  # Output the Public IP of the Public-facing VM
  output "public_vm_ip" {
    value = azurerm_public_ip.public_vm_ip.ip_address
  }
  