#!/bin/bash
# Drupal-7 CiviCRM install script.
# 

#======================================================
# Edit/replace the following with suitable DB values.
#
# NB: In the original script these were accessed using
#     the Puppet brisskit_db_param function call. 
#     For example:
#       host="$(brisskit_db_param ${drupalname} host)"
#       type="$(brisskit_db_param ${drupalname} type)"
#       name="$(brisskit_db_param ${drupalname} name)"
#       user="$(brisskit_db_param ${drupalname} user)"
#       pass="$(brisskit_db_param ${drupalname} pass)"
#
# I've changed the approach because of the dependence on
# a function within the Puppet setup that I wasn't sure
# would be there any time soon.
#
DBTYPE="mysql"
MYSQL_HOST="localhost"
MYSQL_ROOT_UN=""
MYSQL_ROOT_PW=""
MYSQL_CIVICRM_DB="civicrm"
MYSQL_CIVICRM_UN="civicrm"
MYSQL_CIVICRM_PW="br1ssk1t123"
#======================================================

#
# BRISSkit directories.
brisskitvar="/var/local/brisskit"
brisskitetc="/etc/brisskit"

#
# Drupal major version number.
# Don't change this without extensive testing.
drupalversion="drupal-7"

#
# Drupal top level directories.
drupalroot="${brisskitvar}/drupal"
drupalconf="${drupalroot}/conf"
apacheroot="${drupalroot}/site"

#
# The Apache virtual host name.
# This is only used if this is not the default site.
# If the intended virtual host name does not match the local machine name then you can set this manually.
#drupalhost="$(hostname -f)"
#Get the bru name eg bru1 as the hostname rather than bru1-civicrm
drupalhost="$(hostname | cut -d'-' -f1)"

#
# The site sub-directory.
# This adds a path to the site URL.
# e.g.
#     http://hostname/civicrm/....
#
# Set this to '' to install Druapl at the website root.
# e.g.
#     http://hostname/....
#
drupalstub="civicrm"

#
# The name of the Drupal site.
# This is used to set the name of the Drupal database and Apache config file.
drupalname="civicrm" 

#
# The Drupal site directory name within dupal/sites.
# Set this to 'default' to make this the default Drupal site.
drupalsite="default" 

#
# CiviCRM settings.
# Be very careful changing version numbers, we have patching going on now!
#You need to find the patching section below and edit it accordingly.
civicrmroot="${brisskitvar}/civicrm"
civicrmdata="civicrm"
civicrmversion="4.1.3"
civicrmtarfile="civicrm-${civicrmversion}-drupal.tar.gz"
civicrminstall="civicrm-${civicrmversion}-drupal"

#
# Brisskit module settings
brisskit_module_root="${brisskitvar}/brisskit"
#brisskit_module_location="https://svn.rcs.le.ac.uk/BRISSkit/repo1/civicrm/hooks/brisskit/"

#
# Patch root
patch_root="${brisskitvar}/patches"
#patch_location="https://svn.rcs.le.ac.uk/BRISSkit/repo1/civicrm/patches/"

#
# Civi case file repository
case_root="${brisskitvar}/civicases"
#case_location="https://svn.rcs.le.ac.uk/BRISSkit/repo1/civicrm/civicases/"

#
# Generate the Drupal core install path.
if [ "${drupalstub}" != '' ]
then
    drupalcore="${apacheroot}/${drupalstub}"
else
    drupalcore="${apacheroot}"
fi

#/var/local/brisskit/drupal              <- drupalroot
#/var/local/brisskit/drupal/conf         <- drupalconf
#/var/local/brisskit/drupal/site         <- apacheroot
#/var/local/brisskit/drupal/site/civicrm <- drupalcore

#
# Install basic tools.

#Do an repo update first
apt-get update 

    if [ -z "$(which unzip)" ]
    then

        apt-get -y install unzip

    fi

    if [ -z "$(which wget)" ]
    then

        apt-get -y install wget

    fi

#
# Configure firewall.
# https://wiki.ubuntu.com/UncomplicatedFirewall

    #
    # Install and configure firewall.
    if [ -z "$(which ufw)" ]
    then

        apt-get -y install ufw

        ufw default deny
        ufw allow ssh/tcp
        ufw allow http/tcp
        ufw logging on
        ufw --force enable

    fi

#
# Install Postfix mail server

    if [ -z "$(which postfix)" ]
    then

        #
        # Disable the debconf front-end.
        DEBIAN_FRONTEND=noninteractive

        #
        # Set configuration params before the install.
cat | debconf-set-selections << EOF
postfix postfix/root_address string root
postfix postfix/mynetworks string 127.0.0.0/8 [::ffff:127.0.0.0]/104 [::1]/128
postfix postfix/mailname	        string  civicrm.brisskit.org.uk
postfix postfix/recipient_delim	    string
postfix postfix/main_mailer_type    select  Internet Site
postfix postfix/destinations	    string  localhost
postfix postfix/mailbox_limit	    string  51200000
postfix postfix/relayhost	        string
postfix postfix/procmail	        boolean false
postfix postfix/protocols	        select  all
postfix postfix/chattr	            boolean false
EOF

        #
        # Install the service.
        apt-get -y install postfix

        # Reconfigure manually if required.
        # dpkg-reconfigure postfix
        # vi /etc/postfix/main.cf

#
# Send a test email.

#   sendmail -t << EOF
#   To:test@brisskit.org.uk
#   Subject:Test email
#   Test email .... please ignore
#   .
#   EOF

    fi

#


#
# Install password generator.

    if [ -z "$(which pwgen)" ]
    then
        apt-get -y install pwgen
    fi
    
#=================================================================================
# MySQL stuff
# Since the original script was written mysql has become local (ie: co-located)
# rather than on a remote machine.
#=================================================================================
apt-get install -y mysql-client
# Create the civi database...
mysql --user=${MYSQL_ROOT_UN} \
      --password=${MYSQL_ROOT_PW} \
      --execute="CREATE DATABASE ${MYSQL_CIVICRM_DB}"
 
# Create an overall civi user...     
mysql --user=${MYSQL_ROOT_UN} \
      --password=${MYSQL_ROOT_PW} \
      --execute="CREATE USER ${MYSQL_CIVICRM_UN}@${MYSQL_HOST} identified by '${MYSQL_CIVICRM_PW}'"

# Grant everything on the civi database to the overall civi user...
mysql --user=${MYSQL_ROOT_UN} \
      --password=${MYSQL_ROOT_PW} \
      --execute="GRANT ALL ON ${MYSQL_CIVICRM_DB}.* TO ${MYSQL_CIVICRM_UN}@${MYSQL_HOST}"

#
# Database admin functions.
# These will be replaced by the BRISSkit functions installed by Puppet.

    #
    # Create a random password.
    randompass()
        {
         pwgen 22 1
        }

#
# Install Apache.

    #
    # Install Apache webserver
    if [ -z "$(which apache2)" ]
    then

        apt-get -y install apache2

    fi

    #
    # Enable mod-rewrite
    pushd /etc/apache2/mods-enabled

        if [ ! -e rewrite.load ]
        then
            ln -sf ../mods-available/rewrite.load
        fi

    popd

#
# Install PHP.

    #
    # Install PHP
    #if [ -z "$(which php)" ]
    if [ ! -e "/etc/apache2/mods-available/php5.conf" ]
    then

        apt-get -y install php5 php5-mysql php5-gd
        apt-get -y install php5-gmp
    fi

#
# Install PECL uploadprogress library.

    peclini="/etc/php5/apache2/conf.d/uploadprogress.ini"
    pecllib="$(find /usr/lib/php5/ -name uploadprogress.so)"

    #
    # If either the lib or ini files are missing.
    if [ ! -e "${peclini}" -o -z "${pecllib}" ]
    then
    
        #
        # If the library is missing.
        if [ -z "${pecllib}" ]
        then

            #
            # Install make.
            if [ -z "$(which make)" ]
            then

                apt-get -y install make

            fi

            #
            # Install PHP pear libraries.
            if [ -z "$(which pear)" ]
            then

                apt-get -y install php-pear

            fi

            #
            # Install PHP dev libraries.
            if [ -z "$(which pecl)" ]
            then

                apt-get -y install php5-dev

            fi

            #
            # Install the library.
            pecl install uploadprogress

        fi

        #
        # If the ini file is missing
        if [ ! -e "${peclini}" ]
        then
cat > "${peclini}" << EOF
extension=uploadprogress.so
EOF
        fi

        service apache2 restart

    fi

#
# Install Drupal shell (drush).

    #
    # Install Drupal shell (drush).
    if [ -z "$(which drush)" ]
    then

        #
        # Install PHP pear libraries.
        if [ -z "$(which pear)" ]
        then

            apt-get -y install php-pear

        fi

        #
        # Locate the drush metadata.
        pear channel-discover pear.drush.org

        #
        # Install drush.
        pear install drush/drush

    fi

    drush status

#
# Install Drupal.

    if [ ! -d "${drupalroot}" ]
    then
        mkdir -p "${drupalroot}"
    fi

#/var/local/drupal           <- drupalroot
#/var/local/drupal/conf      <- drupalconf
#/var/local/drupal/site      <- apacheroot
#/var/local/drupal/site/stub <- drupalcore

    installpath=$(dirname  ${drupalcore})
    installname=$(basename ${drupalcore})

    echo "Installing Drupal.. "
    echo "core [${drupalcore}]"
    echo "path [${installpath}]"
    echo "name [${installname}]"

    if [ ! -d "${drupalcore}" ]
    then

        if [ ! -d "${installpath}" ]
        then
            mkdir "${installpath}"
        fi

        pushd "${installpath}"

            drush dl "${drupalversion}"  --drupal-project-rename="${installname}"

        popd

    fi

#
# Install common drupal modules.

    pushd "${drupalcore}"

        drush dl 'content_taxonomy'
        drush dl 'ctools'
        drush dl 'date'
        drush dl 'email'
        drush dl 'favicon'
        drush dl 'field_group'
        drush dl 'token'
        drush dl 'views'
        drush dl 'og'

    popd

#
# Create our Drupal site config.

    pushd "${drupalcore}/sites"

        #
        # Create our site directory.
        if [ ! -d "${drupalsite}" ] 
        then

            mkdir "${drupalsite}"

        fi

        pushd "${drupalsite}"

            #
            # If we are NOT the default site.
            if [ 'default' != "${drupalsite}" ]
            then
                #
                # Create the multi-site aliases
                if [ ! -e "sites.php" ] 
                then

                    if [ ! -e "../sites.php" ] 
                    then
cat >> "../sites.php" << EOF
<?php
/**
 * Multi-site directory aliasing:
 *
 */

EOF
                fi

cat >> "../sites.php" << EOF
require '${drupalsite}/sites.php' ;
EOF

cat > "sites.php" << EOF
<?php
/**
 * Multi-site directory aliasing:
 *
 */

\$sites['${drupalhost}'] = '${drupalsite}';

EOF
                fi
            fi

            #
            # Create our site settings.
            if [ ! -e 'settings.php' ] 
            then
            
                host="${MYSQL_HOST}"
                type="${DBTYPE}"
                name="${MYSQL_CIVICRM_DB}"
                user="${MYSQL_CIVICRM_UN}"
                pass="${MYSQL_CIVICRM_PW}"
                salt="$(randompass)"

cat > settings.php << EOF
<?php

/*
 * Database config.
 *
 */
\$databases['default']['default'] = array(
    'driver'    => '${type}',
    'database'  => '${name}',
    'username'  => '${user}',
    'password'  => '${pass}',
    'host'      => '${host}',
    'prefix'    => '',
    'collation' => 'utf8_general_ci',
    );

/**
 * Salt for one-time login links, cancel links and form tokens, etc.
 *
 */
\$drupal_hash_salt = '${salt}';

EOF

            fi

            #
            # Allow Apache to write to the files.
            if [ ! -d "files" ] 
            then

                mkdir "files"
                chgrp 'www-data' "files"
                chmod 'g+rws'    "files"

            fi

            #
            # Allow Apache to modify our settings (required for install).
            chgrp 'www-data' "settings.php"
            chmod 'g+rw'     "settings.php"
            
        popd

    popd

#
# Create our Apache vhost config.

    if [ ! -d "${drupalconf}" ]
    then
        mkdir -p "${drupalconf}"
    fi

    pushd "${drupalconf}"

        if [ ! -e "${drupalname}.conf" ]
        then

#
# Create our Apache config.
cat > "${drupalname}.conf" << EOF
<VirtualHost *:80>

    # With no server name set, this will match any hostname.
    # ServerName  ${drupalhost}

    ServerAdmin  admin@localhost
    DocumentRoot ${apacheroot}
    ErrorLog     \${APACHE_LOG_DIR}/${drupalname}.error.log
    CustomLog    \${APACHE_LOG_DIR}/${drupalname}.access.log common

    php_value include_path "."

    <Directory ${drupalcore}>

        #
        # Allow symbolic links.
        Options FollowSymLinks

        #
        # Set the rewrite rules here.
        RewriteEngine on
        RewriteBase /
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)\$ index.php?q=\$1 [L,QSA]

    </Directory>

</VirtualHost>
EOF

#
# TODO
# For default site, VirtualHost config with no server name will match any hostname.
# For multi site install, add ServerName and ServerAlias.
#

        fi
    popd
    
    #
    # Install our Apache config.
    pushd /etc/apache2/sites-enabled

        ln -sf ${drupalconf}/${drupalname}.conf ${drupalname}.conf

        #
        # Remove the default Apache site.
        if [ -L "000-default" ]
        then
            rm "000-default"
        fi

    popd

    service apache2 reload

#
# Install CiviCRM.

    if [ ! -d "${civicrmroot}" ]
    then
        mkdir -p "${civicrmroot}"
    fi

    pushd "${civicrmroot}"

        if [ ! -d "installs" ]
        then
            mkdir -p "installs"
        fi

        pushd "installs"

            if [ ! -d "${civicrminstall}" ]
            then
                mkdir -p "${civicrminstall}"
            fi

            pushd "${civicrminstall}"

                if [ ! -d "civicrm" ]
                then

                    if [ ! -d "../../zipfiles" ]
                    then
                        mkdir -p "../../zipfiles"
                    fi
                    
                    pushd "../../zipfiles"

                        if [ ! -e "${civicrmtarfile}" ]
                        then
                            wget -q "http://downloads.sourceforge.net/project/civicrm/civicrm-stable/${civicrmversion}/${civicrmtarfile}"
                        fi

                    popd

                    tar -xvzf "../../zipfiles/${civicrmtarfile}"
                fi

            popd

        popd
    popd

#
# Add CiviCRM into Drupal.

    pushd "${drupalcore}/sites/all/modules"

        #
        # If 'civicrm' is already installed, remove it.
        if [ -e  "civicrm" ]
        then
            if [ -L "civicrm" ]
            then
                rm "civicrm"
            else
                rm -f "civicrm"
            fi
        fi
        #
        # Add a link to the new module.
        ln -s ${civicrmroot}/installs/${civicrminstall}/civicrm/ civicrm

    popd

#
# Un-protect our site directory (used by CiviCRM install.php).

    chgrp www-data "${drupalcore}/sites/${drupalsite}"
    chmod g+w      "${drupalcore}/sites/${drupalsite}"

#
# Check our site files directry is writeable.

    if [ ! -e "${drupalcore}/sites/${drupalsite}/files" ]
    then
        mkdir -p         "${drupalcore}/sites/${drupalsite}/files"
        chgrp 'www-data' "${drupalcore}/sites/${drupalsite}/files"
        chmod 'g+rwxs'   "${drupalcore}/sites/${drupalsite}/files"
    fi 


#####################################################################
# There are some bugs in civi that need a patch file applied
# THIS WILL BREAK WHEN WE CHANGE VERSIONS, AND IS NOT ACTUALLY VERY
# ROBUST AT ALL!

#I am hoping I am back in the correct directory now, should probably check!

#Get the patch files
#svn checkout ${patch_location}

#Move to right place
mv patches ${patch_root}

#Apply the patch
patch -bf ${civicrmroot}/installs/${civicrminstall}/civicrm/api/v3/CustomValue.php ${patch_root}/CustomValue4_1_3.patch


#####################################################################
# Install some cases

#Get the case files
#svn checkout ${case_location}

#Move to right place
mv civicases ${case_root}


#####################################################################
# Install the brisskit module

#Get source
#svn checkout ${brisskit_module_location}

#Move to right place
mv hooks/brisskit ${brisskit_module_root}

# Add a link to the new module.
ln -s ${brisskit_module_root} ${drupalcore}/sites/all/modules/brisskit


#####################################################################
# Add the crontab to do the automatic data transfers
crontab cron/crontab



#####################################################################
# Display database settings (for CiviCRM config).
# You will need these when initialising CiviCRM below.

if [ "${drupalstub}" != "" ]
then
    sitehref="http://${drupalhost}.brisskit.le.ac.uk/${drupalstub}"
else
    sitehref="http://${drupalhost}.brisskit.le.ac.uk"
fi

cat << EOF
    -------------------------
    -------------------------
    Drupal/CiviCRM deployment completed.

    Now you need to use a web browser to visit the site and complete the process.

    To complete the Drupal configuration goto :

        ${sitehref}/install.php

    To complete the CiviCRM configuration goto :

        ${sitehref}/sites/all/modules/civicrm/install/index.php

    The CiviCRM configuration page will need the following database settings

        CiviCRM Database Settings

            MySQL server   : ${MYSQL_HOST}
            MySQL username : ${MYSQL_CIVICRM_UN}
            MySQL password : ${MYSQL_CIVICRM_PW}
            MySQL database : ${MYSQL_CIVICRM_DB}

        Drupal Database Settings

            MySQL server   : ${MYSQL_HOST}
            MySQL username : ${MYSQL_CIVICRM_UN}
            MySQL password : ${MYSQL_CIVICRM_PW}
            MySQL database : ${MYSQL_CIVICRM_DB}

    -------------------------
    -------------------------

    Once you have completed the online configuration,
    remember to protect the drupal settings.

    chmod 'g-w' "${drupalcore}/sites/${drupalsite}"
    chmod 'g-w' "${drupalcore}/sites/${drupalsite}/settings.php"

    You also need to enable the brisskit module. You need to be in the 
    module directory to do this, so

    cd ${drupalcore}/sites/all/modules
    
    drush en brisskit,brisskit_datacol,brisskit_useinfo,brisskit_tissue

EOF


#Need to add the brisskit module download and enable a la:
#drush en brisskit,brisskit_datacol,brisskit_useinfo,brisskit_tissue


# TODO 
# File permissions
# https://drupal.org/node/244924

# Disable module install.
# https://drupal.org/documentation/install/modules-themes/modules-7

