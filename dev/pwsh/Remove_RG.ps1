# Delete ProdigiesAI_ResourceGroup
Remove-AzResourceGroup -Name "PRODIGIESAI_RESOURCES" -Force -Verbose

# Delete NetworkWatcherRG
Remove-AzResourceGroup -Name 'NetworkWatcherRG' -Force -Verbose

# Delete BackOffice_ResourceGroup
Remove-AzResourceGroup -Name "BACKOFFICE_RESOURCES" -Force -Verbose
