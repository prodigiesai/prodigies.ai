# Define your resource group and NSG
$resourceGroup = "BackOffice_ResourceGroup"
$nsgName = "BackOffice-NSG"

# Define the SSH rule
$nsgRuleSSH = New-AzNetworkSecurityRuleConfig -Name "Allow-SSH" `
                                               -Description "Allow SSH" `
                                               -Access Allow `
                                               -Protocol Tcp `
                                               -Direction Inbound `
                                               -Priority 110 `
                                               -SourceAddressPrefix "*" `
                                               -SourcePortRange "*" `
                                               -DestinationAddressPrefix "*" `
                                               -DestinationPortRange 22

# Get the existing NSG
$nsg = Get-AzNetworkSecurityGroup -ResourceGroupName $resourceGroup -Name $nsgName

# Add the rule to the NSG
$nsg.SecurityRules.Add($nsgRuleSSH)

# Apply the changes to the NSG
Set-AzNetworkSecurityGroup -NetworkSecurityGroup $nsg
