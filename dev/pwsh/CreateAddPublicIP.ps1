$resourceGroupName = "ProdigiesAI_ResourceGroup"
$backoffice_resourceGroup = "BackOffice_ResourceGroup"
$location = "EastUS2"
$vnetName = "ProdigiesAI-VNet"
$subnetName = "default"
$publicIpName = "BackOffice-PublicIP"
$vmNamePrivate = "BackOffice-VM"
$nicNamePrivate = "BackOffice-VM"



# Login to Azure account
# Connect-AzAccount   

# Create the public IP address
$publicIp = New-AzPublicIpAddress -ResourceGroupName $backoffice_resourceGroup -Name $publicIpName -Location $location -AllocationMethod Static -Sku Standard -Force


# Get the NIC of the VM
$nic = Get-AzNetworkInterface -ResourceGroupName $backoffice_resourceGroup -Name $nicNamePrivate  # Replace with the correct NIC name

    

    # Assign the public IP to the NIC
$nic.IpConfigurations[0].PublicIpAddress = $publicIp

# Update the NIC to apply changes
Set-AzNetworkInterface -NetworkInterface $nic

# Check the NIC details to confirm the public IP
Get-AzNetworkInterface -ResourceGroupName $backoffice_resourceGroup -Name $nicNamePrivate