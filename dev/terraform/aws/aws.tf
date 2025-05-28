# Provider
provider "aws" {
  region = var.aws_region
}

# Variables
variable "aws_region" {
  default = "us-east-1"
}

variable "admin_username" {
  default = "aiadmin"
}

variable "admin_password" {
  description = "Admin password for EC2 instances."
  type        = string
  sensitive   = true
}

variable "instance_type" {
  default = "t2.micro"
}

# VPC (Equivalent of Azure Virtual Network)
resource "aws_vpc" "shared_vpc" {
  cidr_block = "10.0.0.0/16"
}

# Public Subnet (equivalent of Azure Public Subnet)
resource "aws_subnet" "public_subnet" {
  vpc_id            = aws_vpc.shared_vpc.id
  cidr_block        = "10.0.1.0/24"
  map_public_ip_on_launch = true
  availability_zone = "us-east-1a"
}

# Private Subnet (equivalent of Azure Private Subnet)
resource "aws_subnet" "private_subnet" {
  vpc_id            = aws_vpc.shared_vpc.id
  cidr_block        = "10.0.2.0/24"
  availability_zone = "us-east-1a"
}

# Internet Gateway (for public access)
resource "aws_internet_gateway" "igw" {
  vpc_id = aws_vpc.shared_vpc.id
}

# Route Table for public subnet
resource "aws_route_table" "public_route_table" {
  vpc_id = aws_vpc.shared_vpc.id
}

resource "aws_route" "public_route" {
  route_table_id         = aws_route_table.public_route_table.id
  destination_cidr_block = "0.0.0.0/0"
  gateway_id             = aws_internet_gateway.igw.id
}

resource "aws_route_table_association" "public_subnet_association" {
  subnet_id      = aws_subnet.public_subnet.id
  route_table_id = aws_route_table.public_route_table.id
}

# Security Group for Public VM (like NSG in Azure)
resource "aws_security_group" "public_sg" {
  vpc_id = aws_vpc.shared_vpc.id

  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# Security Group for Private VM (like Private NSG in Azure)
resource "aws_security_group" "private_sg" {
  vpc_id = aws_vpc.shared_vpc.id

  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["10.0.1.0/24"]  # Allow SSH traffic from public subnet
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# EC2 Instance (equivalent of Public VM in Azure)
resource "aws_instance" "public_vm" {
  ami           = "ami-123456"  # Replace with Ubuntu AMI in your region
  instance_type = var.instance_type
  key_name      = "my-key"  # Replace with your SSH key
  subnet_id     = aws_subnet.public_subnet.id
  security_groups = [aws_security_group.public_sg.name]

  user_data = <<-EOF
              #!/bin/bash
              sudo apt update
              sudo apt install -y python3.8 ansible
              EOF
}

# EC2 Instance (equivalent of Private VM in Azure)
resource "aws_instance" "private_vm" {
  ami           = "ami-123456"  # Replace with Ubuntu AMI in your region
  instance_type = var.instance_type
  subnet_id     = aws_subnet.private_subnet.id
  security_groups = [aws_security_group.private_sg.name]

  user_data = <<-EOF
              #!/bin/bash
              sudo apt update
              sudo apt install -y python3.8 mariadb-server
              EOF
}

# Output public and private IP addresses
output "public_vm_ip" {
  value = aws_instance.public_vm.public_ip
}

output "private_vm_ip" {
  value = aws_instance.private_vm.private_ip
}
