# Installing Terraform

brew tap hashicorp/tap  # Add the HashiCorp tap to Homebrew
brew install hashicorp/tap/terraform  # Install Terraform via Homebrew
terraform --version  # Check the installed version of Terraform

# General Terraform Commands

terraform init  # Initializes a new or existing Terraform configuration
terraform validate  # Validates the configuration files
terraform fmt  # Formats Terraform configuration files to a canonical style
terraform plan  # Creates an execution plan to show actions Terraform will take
terraform apply  # Applies the changes required to reach the desired state
terraform destroy  # Destroys the infrastructure managed by Terraform
terraform show  # Displays the state or a plan with detailed information

# State Management

terraform state list  # Lists all resources in the current state
terraform state show <resource>  # Shows the details of a specific resource in the state
terraform state rm <resource>  # Removes a resource from the state file
terraform state pull  # Downloads the state from the remote backend
terraform state push  # Uploads a local state file to the remote backend

# Resource and Module Management

terraform taint <resource>  # Marks a resource for recreation on the next apply
terraform untaint <resource>  # Removes the taint from a resource (prevents recreation)
terraform import <resource> <id>  # Imports an existing resource into Terraform management

# Workspaces

terraform workspace list  # Lists all available workspaces
terraform workspace new <name>  # Creates a new workspace
terraform workspace select <name>  # Switches to another workspace
terraform workspace delete <name>  # Deletes an existing workspace

# Remote Backend Commands

terraform login  # Authenticates with Terraform Cloud for remote operations
terraform logout  # Logs out from Terraform Cloud and removes credentials

# Variables and Outputs

terraform output  # Displays the output values defined in the Terraform configuration
terraform output -json  # Displays the outputs in JSON format
terraform console  # Opens an interactive console to evaluate expressions and debug

# Provisioning and Debugging

terraform refresh  # Updates the state file with the latest infrastructure information
terraform graph  # Outputs a visual graph of resources and dependencies in DOT format

# Locking and Unlocking States

terraform force-unlock <lock-id>  # Manually unlocks the state if locking is stuck

# Best Practices

terraform apply -var-file="prod.tfvars"  # Use a .tfvars file for variables
# Enable version locking for modules/providers
# Example:
provider "aws" {
  version = "~> 3.0"
}
# Use remote backends for state storage to avoid local state issues
# Example using S3 or Terraform Cloud
terraform init  # Initialize the remote backend for state storage
