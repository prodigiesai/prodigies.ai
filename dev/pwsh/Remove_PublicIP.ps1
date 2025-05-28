# Define Variables
$resourceGroupName = "ProdigiesAI_ResourceGroup"
$backoffice_resourceGroup = "BackOffice_ResourceGroup"
$publicIpName = "BackOffice-PublicIP"
$nicNamePrivate = "BackOffice-VM"

# Get the NIC of the VM
$nic = Get-AzNetworkInterface -ResourceGroupName $backoffice_resourceGroup -Name $nicNamePrivate

# Disassociate the Public IP from the NIC
$nic.IpConfigurations[0].PublicIpAddress = $null

# Update the NIC to apply changes
Set-AzNetworkInterface -NetworkInterface $nic

# Delete the Public IP address
Remove-AzPublicIpAddress -ResourceGroupName $backoffice_resourceGroup -Name $publicIpName -Force
# Remove-AzPublicIpAddress -ResourceGroupName "ProdigiesAI_ResourceGroup" -Name "BackOffice-PublicIP" -Force

# Optionally, check the NIC details to confirm the Public IP is disassociated
Get-AzNetworkInterface -ResourceGroupName $backoffice_resourceGroup -Name $nicNamePrivate
