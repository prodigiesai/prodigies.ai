sudo apt install iptables


sudo iptables -t nat -A PREROUTING -p tcp --dport 2222 -j DNAT --to-destination <other-server-ip>:22
sudo iptables -t nat -A POSTROUTING -p tcp -d <other-server-ip> --dport 22 -j MASQUERADE


sudo iptables-save > /etc/iptables/rules.v4

# Testing the Setup
ssh <proxy-user>@<proxy-ip>
ssh -p 2222 <other-server-user>@<proxy-ip>
