# variables.tf
variable "subscription_id" {
  description = "Azure subscription ID."
  type        = string
}

provider "azurerm" {
  features {}
  subscription_id = var.subscription_id
}

# Variables
variable "location" {
  default = "EastUS2"
}

variable "admin_username" {
  default = "aiadmin"
}

variable "admin_password" {
  description = "Admin password for virtual machines."
  type        = string
  sensitive   = true
}

variable "vm_size" {
  default = "Standard_D2s_v4"
}