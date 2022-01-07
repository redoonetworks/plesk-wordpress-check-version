# plesk-wordpress-check-version

This repo contains a php script for command line, which scan a complete plesk server for existing wordpress installations, check if IP is configured to current IP and if domain is still active and reachable.

Usage
-----

To run the script, at first find all versions.php on server:

`find /var/www/vhosts/ -name "version.php"  -print0 | xargs -0 grep '$wp_version =' > /tmp/output.txt`

Then execute this script:

`php -f filter.php`  
It also can define wordpress versions, which are safe and should be ignored.  
It will handle domains and subdomains only, when wordpress is installed within the root folder of domain/subdomain.  

Output
------
The output is a list of versions and paths, including domain, you should check or inform your client, because version is vulnerable and domain works on your server.
