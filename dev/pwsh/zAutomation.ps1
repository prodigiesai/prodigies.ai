# Variables
$resourceGroup = "ProdigiesAI_ResourceGroup"
$location = "EastUS2"


# Create the Automation Account
Write-Verbose "Creating  the Automation Account..."
New-AzAutomationAccount -ResourceGroupName $resourceGroup -Location $location -Name "ProdigiesAIAutomationAccount"


# Import the necessary modules for managing VMs in Azure Automation
Write-Verbose "Import the necessary modules for managing VMs in Azure Automation..."
Import-AzAutomationModule -ResourceGroupName $resourceGroup -AutomationAccountName "ProdigiesAIAutomationAccount" -Name "Az.Compute"
Import-AzAutomationModule -ResourceGroupName $resourceGroup -AutomationAccountName "ProdigiesAIAutomationAccount" -Name "Az.Accounts"


# Start VM Rubbooks
Write-Verbose "Creating VM Rubbooks..."
$startVMRunbook = @"
param (
    [string]`$ResourceGroupName = '$resourceGroup',
    [string]`$VMName = 'ProdigiesAI-VM'
)
Connect-AzAccount
Start-AzVM -ResourceGroupName `\$ResourceGroupName -Name `\$VMName
"@

New-AzAutomationRunbook -ResourceGroupName $resourceGroup -AutomationAccountName "ProdigiesAIAutomationAccount" -Name "Start-VM" -Type PowerShell -Description "Runbook to start VMs" -LogProgress $false -LogVerbose $true
Set-AzAutomationRunbookContent -ResourceGroupName $resourceGroup -AutomationAccountName "ProdigiesAIAutomationAccount" -Name "Start-VM" -ScriptContent $startVMRunbook


$startVMRunbook = @"
param (
    [string]`$ResourceGroupName = '$resourceGroup',
    [string]`$VMName = 'ProdigiesAI-VM'
)
Connect-AzAccount
Start-AzVM -ResourceGroupName `\$ResourceGroupName -Name `\$VMName
"@

New-AzAutomationRunbook -ResourceGroupName $resourceGroup -AutomationAccountName "ProdigiesAIAutomationAccount" -Name "Start-VM" -Type PowerShell -Description "Runbook to start VMs" -LogProgress $false -LogVerbose $true
Set-AzAutomationRunbookContent -ResourceGroupName $resourceGroup -AutomationAccountName "ProdigiesAIAutomationAccount" -Name "Start-VM" -ScriptContent $startVMRunbook

# Stop VM Rubbooks
$stopVMRunbook = @"
param (
    [string]`$ResourceGroupName = '$resourceGroup',
    [string]`$VMName = 'ProdigiesAI-VM'
)
Connect-AzAccount
Stop-AzVM -ResourceGroupName `\$ResourceGroupName -Name `\$VMName -Force
"@

New-AzAutomationRunbook -ResourceGroupName $resourceGroup -AutomationAccountName "ProdigiesAIAutomationAccount" -Name "Stop-VM" -Type PowerShell -Description "Runbook to stop VMs" -LogProgress $false -LogVerbose $true
Set-AzAutomationRunbookContent -ResourceGroupName $resourceGroup -AutomationAccountName "ProdigiesAIAutomationAccount" -Name "Stop-VM" -ScriptContent $stopVMRunbook


# Create a schedule to start the VM at 8:00 AM EST
Write-Verbose "Create a schedule to start the VM at 8:00 AM EST..."
$startSchedule = New-AzAutomationSchedule -AutomationAccountName "ProdigiesAIAutomationAccount" -ResourceGroupName $resourceGroup -Name "Start-VM-Schedule" -StartTime (Get-Date "08:00AM").ToUniversalTime() -DayInterval 1

# Link the schedule to the Start-VM Runbook
Register-AzAutomationScheduledRunbook -AutomationAccountName "ProdigiesAIAutomationAccount" -ResourceGroupName $resourceGroup -RunbookName "Start-VM" -ScheduleName "Start-VM-Schedule"

# Create a schedule to stop the VM at 6:00 PM EST
Write-Verbose "Create a schedule to stop the VM at 6:00 PM EST"
$stopSchedule = New-AzAutomationSchedule -AutomationAccountName "ProdigiesAIAutomationAccount" -ResourceGroupName $resourceGroup -Name "Stop-VM-Schedule" -StartTime (Get-Date "06:00PM").ToUniversalTime() -DayInterval 1

# Link the schedule to the Stop-VM Runbook
Register-AzAutomationScheduledRunbook -AutomationAccountName "ProdigiesAIAutomationAccount" -ResourceGroupName $resourceGroup -RunbookName "Stop-VM" -ScheduleName "Stop-VM-Schedule"
