#!/bin/bash

# Array of products and hashtags
declare -A products
products["BTC-USD"]="BTC,Bitcoin"
products["ETH-USD"]="ETH,Ethereum"
products["LTC-USD"]="LTC,Litecoin"

# Iterate over products
for product in "${!products[@]}"; do
    hashtags="${products[$product]}"
    timestamp=$(date +"%Y%m%d%H%M%S")
    logfile="${product}.${timestamp}.log"

    # Run Python script in the background and log output
    /Users/obarrientos/Documents/Prodigies/apps/crypto/.venv/bin/python3 /Users/obarrientos/Documents/Prodigies/apps/crypto/run_crypto.py --product "$product" --hashtags "$hashtags" --prediction_timeframe 60 > "/Users/obarrientos/Documents/Prodigies/apps/crypto/logs/$logfile" 2>&1 &
done