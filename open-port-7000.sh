#!/bin/bash

# Script to open port 7000 in the firewall for WordPress development

echo "Opening port 7000 in firewall..."
echo ""

# Check if UFW is active
if sudo ufw status | grep -q "Status: active"; then
    echo "UFW firewall is active, adding rule for port 7000..."
    sudo ufw allow 7000/tcp
    echo ""
    echo "✓ Port 7000 is now open in UFW firewall"
    echo ""
    sudo ufw status | grep 7000
else
    echo "UFW firewall is not active or not installed"
    echo "Your port 7000 should already be accessible"
fi

echo ""
echo "WordPress should now be accessible at:"
echo "  → http://192.168.10.203:7000"
echo ""

