# This is my home site on HP-Envy

I am using lfs for big files (https://git-lfs.github.com/).

----

# How to Get HTTPS to work with Suddenlink

Suddenlink (bless there harts) blocks ports 25 and 80. They sell business plans that have these port open but not with a DVR for TV.
So if you want a website from your home and a DVR for your cable TV your are out of luck.


However, port 443 is still open so you can get an HTTPS cert from [Lets Encrypt](https://letsencrypt.org/) by using the following:

      sudo certbot certonly --manual --preferred-challenges dns -d bartonphillips.org

__Certbot__ gives you a secret TXT key to add to the DNS record for *bartonphillips.org* at [DigitalOcean](https://digitalocian.com).
The key that __certbot__ gives you  must be reset each time. __Certbot__ will give you a new key and you
need to add it to the DNS TXT record each time you renew.

Make a __CNAME__: _acme-challenge (_acme-challenge.bartonphillips.org) under *bartonphillips.org*.  
Then it all works.

----

# How we update the A record at DigitalOcian

I use dynamic DNS via [DynDns](https://dyndns.org), and a third party *updater* __ddclient__ to update the information at *dyndns.org*.
However I still need to update the __A record__ at [DigitalOcean](https://digitalocian.com).

To update the __A record__ for *bartonphillips.org* I use a bash file (/home/barton/updateDNS.sh on my home system):
```
#!/bin/bash
# This file updates the DNS record for bartonphillips.org at
# digitalocean.com. We check the 'dnsARecord' file to see if the
# bartonphillips.dyndns.org domain has the same DNS record as is in the
# file 'dnsARecord'. If it does we do nothing.
# Else we update the DNS record at digitalocian.com.

DIGITALOCEAN_TOKEN={secret token}; # the secret token from digitalocian 
ID={secret ID}; # my ID at digitalocian (see updateDNS.sh for actual token and ID)

input="dnsARecord";
while read -r line
do
  y=$line;
done < $input;

x=$(dig +short bartonphillips.dyndns.org);
echo "Current IP: $y";
echo "host bartonphillips.dyndns.org: $x";

if [ "$x" = "$y" ]; then
  echo "Current IP is the same as from bartonphillips.dyndns.org";
  exit 0;
fi;

echo $x > dnsARecord;

echo "Do curl using new A record IP: $x";

curl -X PUT \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $DIGITALOCEAN_TOKEN" \
  -d '{"data":"'$x'"}' \
  "https://api.digitalocean.com/v2/domains/bartonphillips.org/records/$ID";

echo;
echo "Done Update";
```

This file also uses *dnsARecord* to hold the last IP.

----

# How to get the GM107 (GeForce 940MX) to use HDMI sound after an upgrade

This is how to get the sound working again if it stops due to a driver update.

First check the 'Menu->Additional Drivers' to see that the nvidia driver is in use.

Second do: egrep '0x10de.*auto' /lib/udev/rules.d

This should show line with: ="auto"  
They should be in: 71-nvidia.rules  
As root, edit the file: nano 71-nvidia.rules  
Change the line with: ="auto"  
to: ="on"  

Save the file.

Then do: update-initramfs -u

And reboot.

With any luck the sound will work. Goto the speaker icon and check the settings->Hardware and see if GM 107 is on. Select it and test.
If GM107 does not say 'OFF' all is well and you should have sound again.

----

# bartonphillips.org
