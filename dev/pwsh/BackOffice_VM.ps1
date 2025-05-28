# Start/Stop VM
# Start-AzVM -ResourceGroupName 'BackOffice_ResourceGroup' -Name 'BackOffice-VM'
# Stop-AzVM -ResourceGroupName 'BackOffice_ResourceGroup' -Name 'BackOffice-VM'
# Variables
$resourceGroup = "BackOffice_ResourceGroup"
$location = "EastUS2"
$vnetName = "BackOffice-VNet"
$subnetName = "default"

$nsgNamePrivate = "BackOffice-NSG"
$adminUsername = "aiadmin"
$adminPassword = ConvertTo-SecureString "P@ssw0rd123!" -AsPlainText -Force
$credential = New-Object System.Management.Automation.PSCredential($adminUsername, $adminPassword)

# VM2 (Private, Vault and Databases) Details - Call it "BackOffice"
$vmNamePrivate = "BackOffice-VM"
$vmSize = "Standard_D2s_v4"
$nicNamePrivate = "$vmNamePrivate-NIC"
$ipConfigName = "$vmNamePrivate-IPConfig"
$vmDiskName = "$vmNamePrivate-Disk"
$vmDiskSize = "30"
$vmDiskaccountType = "Standard_LRS"
$tags += @{Servers="Virtual Machine"}

Write-Verbose "Creating BackOffice Resource Group..."
New-AzResourceGroup -Name $resourceGroup  -Location $location


# 6. Create Network Security Group for Private VM (BackOffice-VM)
Write-Verbose "Creating Network Security Group for Private VM..."
$nsgPrivate = New-AzNetworkSecurityGroup -ResourceGroupName $resourceGroup -Location $location -Name $nsgNamePrivate -Force

# 5. Add Inbound Rules to Allow SSH and HTTP/HTTPS Traffic on the Public VM (ProdigiesAI-VM)
Write-Verbose "Adding security rules to NSG for Public-facing VM..."
$nsgRuleSSH = New-AzNetworkSecurityRuleConfig -Name "Allow-SSH" -Description "Allow SSH" -Access Allow -Protocol Tcp -Direction Inbound -Priority 100 -SourceAddressPrefix "*" -SourcePortRange "*" -DestinationAddressPrefix "*" -DestinationPortRange 22

# Apply the rules to the NSG for Public VM
$nsgPublic.SecurityRules.Add($nsgRuleSSH)
Set-AzNetworkSecurityGroup -NetworkSecurityGroup $nsgPublic


# 7. Add a Rule to Allow Only Subnet Traffic to the Private VM (BackOffice-VM)
Write-Verbose "Adding subnet access rule to NSG for Private VM..."
$nsgRuleSubnetAccess = New-AzNetworkSecurityRuleConfig -Name "Allow-Subnet-Access" -Description "Allow Subnet Access" -Access Allow -Protocol Tcp -Direction Inbound -Priority 100 -SourceAddressPrefix "VirtualNetwork" -SourcePortRange "*" -DestinationAddressPrefix "*" -DestinationPortRange "*"


# Apply the rule to the NSG for Private VM
$nsgPrivate.SecurityRules.Add($nsgRuleSubnetAccess)
Set-AzNetworkSecurityGroup -NetworkSecurityGroup $nsgPrivate

# Create UserData value
$UserDataStr = @'
#!/bin/bash
sudo apt-get update

# Install Kubernetes (K3s for lightweight Kubernetes)
curl -sfL https://get.k3s.io | sh -
sudo systemctl start k3s
sudo systemctl enable k3s

# Confirm Kubernetes (K3s) installation
k3s kubectl get nodes

# Optionally install any other tools needed for managing Kubernetes
echo "Kubernetes (K3s) is up and running on BackOffice-VM"
'@;


$userDataPrivateVM = [Convert]::ToBase64String([System.Text.Encoding]::UTF8.GetBytes($UserDataStr))
New-AzVM -ResourceGroupName $resourceGroup -Location $location -Name $vmNamePrivate -Size $vmSize -VirtualNetworkName $vnetName -SubnetName $subnetName -SecurityGroupName $nsgNamePrivate  -Credential $credential -ImageName "Canonical:UbuntuServer:18.04-LTS:latest" -UserData $userDataPrivateVM -Verbose

# Set the tag
Set-AzResource -Name $vmNamePrivate -ResourceGroupName $resourceGroup -ResourceType "Microsoft.Compute/VirtualMachines" -Tag $tags -Force

# Stop the VM
Stop-AzVM -Name $vmNamePrivate -ResourceGroupName $resourceGroup -Force

# Write completion message
Write-Host "Azure VM creation completed" -foreground green

# Clear variables
Remove-Variable * -ErrorAction SilentlyContinue
