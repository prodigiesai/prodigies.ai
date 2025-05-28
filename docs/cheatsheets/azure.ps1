# Azure PowerShell Cheat Sheet

# -----------------------------
# 1. Login to Azure
# -----------------------------

# Login to Azure account
Connect-AzAccount

# Check Azure account info
Get-AzSubscription

# Select a specific subscription
Set-AzContext -SubscriptionId 'your-subscription-id'

# -----------------------------
# 2. Resource Group Management
# -----------------------------

# Create a resource group
New-AzResourceGroup -Name 'myResourceGroup' -Location 'EastUS'

# List all resource groups
Get-AzResourceGroup

# Delete a resource group
Remove-AzResourceGroup -Name 'myResourceGroup' -Force

# -----------------------------
# 3. Virtual Machine (VM) Management
# -----------------------------

# Create a virtual machine
New-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM' -Location 'EastUS' -VirtualNetworkName 'myVNet' -SubnetName 'mySubnet' -SecurityGroupName 'myNetworkSecurityGroup' -PublicIpAddressName 'myPublicIP' -OpenPorts 80,3389

# Start a virtual machine
Start-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM'

# Stop a virtual machine
Stop-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM'

# Delete a virtual machine
Remove-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM'

# Get details about a virtual machine
Get-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM'

# -----------------------------
# 4. Storage Management
# -----------------------------

# Create a storage account
New-AzStorageAccount -ResourceGroupName 'myResourceGroup' -Name 'mystorageaccount' -Location 'EastUS' -SkuName 'Standard_LRS'

# List all storage accounts
Get-AzStorageAccount

# Create a new storage container
$context = New-AzStorageContext -StorageAccountName 'mystorageaccount' -StorageAccountKey (Get-AzStorageAccountKey -ResourceGroupName 'myResourceGroup' -Name 'mystorageaccount').Value[0]
New-AzStorageContainer -Name 'mycontainer' -Context $context -Permission blob

# Upload a file to a blob storage
Set-AzStorageBlobContent -File "C:\file.txt" -Container "mycontainer" -Blob "file.txt" -Context $context

# -----------------------------
# 5. Azure SQL Database Management
# -----------------------------

# Create an Azure SQL Database server
New-AzSqlServer -ResourceGroupName 'myResourceGroup' -ServerName 'mySqlServer' -Location 'EastUS' -SqlAdministratorCredentials (Get-Credential)

# Create a SQL Database
New-AzSqlDatabase -ResourceGroupName 'myResourceGroup' -ServerName 'mySqlServer' -DatabaseName 'myDatabase' -Edition 'Standard' -RequestedServiceObjectiveName 'S0'

# List all databases in the server
Get-AzSqlDatabase -ResourceGroupName 'myResourceGroup' -ServerName 'mySqlServer'

# Export SQL Database to a .bacpac file
New-AzSqlDatabaseExport -ResourceGroupName 'myResourceGroup' -ServerName 'mySqlServer' -DatabaseName 'myDatabase' -StorageKeyType 'StorageAccessKey' -StorageUri 'https://mystorageaccount.blob.core.windows.net/backups/myDatabase.bacpac' -StorageKey (Get-AzStorageAccountKey -ResourceGroupName 'myResourceGroup' -Name 'mystorageaccount').Value[0] -AdministratorLogin 'adminuser' -AdministratorLoginPassword (Get-Credential).Password

# Import SQL Database from a .bacpac file
New-AzSqlDatabaseImport -ResourceGroupName 'myResourceGroup' -ServerName 'mySqlServer' -DatabaseName 'myDatabase' -StorageKeyType 'StorageAccessKey' -StorageUri 'https://mystorageaccount.blob.core.windows.net/backups/myDatabase.bacpac' -StorageKey (Get-AzStorageAccountKey -ResourceGroupName 'myResourceGroup' -Name 'mystorageaccount').Value[0] -AdministratorLogin 'adminuser' -AdministratorLoginPassword (Get-Credential).Password

# Delete a SQL Database
Remove-AzSqlDatabase -ResourceGroupName 'myResourceGroup' -ServerName 'mySqlServer' -DatabaseName 'myDatabase'

# -----------------------------
# 6. Azure Backup
# -----------------------------

# Create a Recovery Services vault
New-AzRecoveryServicesVault -ResourceGroupName 'myResourceGroup' -Name 'myRecoveryServicesVault' -Location 'EastUS'

# Set vault context
$vault = Get-AzRecoveryServicesVault -ResourceGroupName 'myResourceGroup' -Name 'myRecoveryServicesVault'
Set-AzRecoveryServicesVaultContext -Vault $vault

# Enable backup for an Azure VM
$policy = Get-AzRecoveryServicesBackupProtectionPolicy -Name 'DefaultPolicy'
Enable-AzRecoveryServicesBackupProtection -Policy $policy -Name 'myVM' -ResourceGroupName 'myResourceGroup'

# Trigger an on-demand backup
Backup-AzRecoveryServicesBackupItem -Item (Get-AzRecoveryServicesBackupItem -Container (Get-AzRecoveryServicesBackupContainer -ContainerType 'AzureVM' -FriendlyName 'myVM') -WorkloadType 'AzureVM')

# List all backup jobs
Get-AzRecoveryServicesBackupJob

# Restore a VM from backup
Restore-AzRecoveryServicesBackupItem -Item (Get-AzRecoveryServicesBackupItem -Container (Get-AzRecoveryServicesBackupContainer -ContainerType 'AzureVM' -FriendlyName 'myVM') -WorkloadType 'AzureVM') -RecoveryType 'RestoreDisks'

# -----------------------------
# 7. Monitoring and Optimization
# -----------------------------

# Monitor the CPU usage of a VM
Get-AzMetric -ResourceId (Get-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM').Id -MetricName 'Percentage CPU' -StartTime (Get-Date).AddDays(-1) -EndTime (Get-Date)

# View disk space usage
Get-AzMetric -ResourceId (Get-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM').Id -MetricName 'Disk Read Bytes' -StartTime (Get-Date).AddDays(-1) -EndTime (Get-Date)

# View network traffic
Get-AzMetric -ResourceId (Get-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM').Id -MetricName 'Network In Total' -StartTime (Get-Date).AddDays(-1) -EndTime (Get-Date)

# -----------------------------
# 8. Azure Load Balancer Management
# -----------------------------

# Create a public IP for the load balancer
$publicIP = New-AzPublicIpAddress -Name 'myPublicIP' -ResourceGroupName 'myResourceGroup' -Location 'EastUS' -AllocationMethod 'Static'

# Create the frontend configuration
$frontendIPConfig = New-AzLoadBalancerFrontendIpConfig -Name 'myFrontendConfig' -PublicIpAddress $publicIP

# Create backend pool
$backendAddressPool = New-AzLoadBalancerBackendAddressPoolConfig -Name 'myBackendPool'

# Create load balancer
$loadBalancer = New-AzLoadBalancer -ResourceGroupName 'myResourceGroup' -Name 'myLoadBalancer' -Location 'EastUS' -FrontendIpConfiguration $frontendIPConfig -BackendAddressPool $backendAddressPool

# -----------------------------
# 9. Azure Virtual Network (VNet) Management
# -----------------------------

# Create a Virtual Network (VNet)
New-AzVirtualNetwork -ResourceGroupName 'myResourceGroup' -Location 'EastUS' -Name 'myVNet' -AddressPrefix '10.0.0.0/16' -SubnetName 'mySubnet' -SubnetPrefix '10.0.0.0/24'

# List all VNets
Get-AzVirtualNetwork

# Delete a VNet
Remove-AzVirtualNetwork -Name 'myVNet' -ResourceGroupName 'myResourceGroup'

# -----------------------------
# 10. Azure Active Directory (AAD) Management
# -----------------------------

# Create a new Azure AD user
New-AzADUser -DisplayName 'John Doe' -UserPrincipalName 'john.doe@mydomain.com' -Password (Read-Host -Prompt 'Enter password' -AsSecureString)

# List all Azure AD users
Get-AzADUser

# Delete an Azure AD user
Remove-AzADUser -UserPrincipalName 'john.doe@mydomain.com'

# -----------------------------
# 11. Azure Automation and Scripting
# -----------------------------

# Create a new Automation Account
New-AzAutomationAccount -ResourceGroupName 'myResourceGroup' -Name 'myAutomationAccount' -Location 'EastUS'

# Create a new runbook
New-AzAutomationRunbook -AutomationAccountName 'myAutomationAccount' -Name 'myRunbook' -Type 'PowerShell' -ResourceGroupName 'myResourceGroup'

# Start a runbook
Start-AzAutomationRunbook -AutomationAccountName 'myAutomationAccount' -Name 'myRunbook' -ResourceGroupName 'myResourceGroup'

# -----------------------------
# 12. Miscellaneous Commands
# -----------------------------

# Check your Azure subscription usage
Get-AzConsumptionUsageDetail -StartDate (Get-Date).AddDays(-30) -EndDate (Get-Date)

# List all Azure locations
Get-AzLocation

# Check quota limits for a resource group
Get-AzVMUsage -Location 'EastUS'

# -----------------------------
# 13. Cleanup Commands
# -----------------------------

# Delete a resource group and all its resources
Remove-AzResourceGroup -Name 'myResourceGroup' -Force

# Remove a VM
Remove-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM' -Force

# Remove a storage account
Remove-AzStorageAccount -ResourceGroupName 'myResourceGroup' -Name 'mystorageaccount'



# -----------------------------
# 13. Listar IPs
# -----------------------------

# Listar las IPs públicas y privadas de una VM
Get-AzVM -ResourceGroupName 'myResourceGroup' -Name 'myVM' | Select-Object -ExpandProperty NetworkProfile | ForEach-Object { $_.NetworkInterfaces.Id } | ForEach-Object { Get-AzNetworkInterface -ResourceId $_ } | Select-Object Name, @{Name="PrivateIP";Expression={$_.IpConfigurations.PrivateIpAddress}}, @{Name="PublicIP";Expression={($_.IpConfigurations.PublicIpAddress.Id | Get-AzPublicIpAddress).IpAddress}}


# Obtener IP pública de todas las VMs en el grupo de recursos
Get-AzVM -ResourceGroupName 'myResourceGroup' | ForEach-Object {
    $nic = Get-AzNetworkInterface -ResourceId $_.NetworkProfile.NetworkInterfaces[0].Id
    $ip = Get-AzPublicIpAddress -ResourceGroupName 'myResourceGroup' -Name $nic.IpConfigurations[0].PublicIpAddress.Id.Split('/')[-1]
    "$($_.Name): $($ip.IpAddress)"
}


# Obtener IPs públicas de todas las VMs en la suscripción
Get-AzVM | ForEach-Object {
    $nic = Get-AzNetworkInterface -ResourceId $_.NetworkProfile.NetworkInterfaces[0].Id
    $publicIpId = $nic.IpConfigurations[0].PublicIpAddress.Id
    if ($publicIpId) {
        $publicIp = Get-AzPublicIpAddress -ResourceId $publicIpId
        "$($_.Name): $($publicIp.IpAddress)"
    }
}


# Obtener IPs privadas de todas las VMs en un grupo de recursos
Get-AzVM -ResourceGroupName 'myResourceGroup' | ForEach-Object {
    $nic = Get-AzNetworkInterface -ResourceId $_.NetworkProfile.NetworkInterfaces[0].Id
    "$($_.Name): $($nic.IpConfigurations[0].PrivateIpAddress)"
}


# Listar todas las IPs públicas de un grupo de recursos
Get-AzPublicIpAddress -ResourceGroupName 'myResourceGroup' | Select-Object Name, IpAddress
