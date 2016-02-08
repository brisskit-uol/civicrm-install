if [[ $EUID -ne 0 ]]
then
    echo "The script must run as root (or sudo)"
    exit 1
fi


pushd `dirname "$0"`

source ./procedures/bin/install-config.sh
source ./procedures/bin/set-install-paths.sh

CRMroot="${drupalcore}/sites/all/modules//civicrm/CRM"
origCRMroot="${civicrmroot}/installs/${civicrminstall}/civicrm/CRM"
extensions_dir="${drupalcore}/sites/${drupalsite}/files/civicrm/custom_ext"


# Files back to vanilla civicrm, except for config
source vanilla.sh

if [ ! -d "${extensions_dir}" ]
then
  mkdir -p "${extensions_dir}"
fi

rm -r ${extensions_dir}/uk.ac.le.brisskit/brisskit.civix.php
rm -r ${extensions_dir}/uk.ac.le.brisskit/brisskit.css
rm -r ${extensions_dir}/uk.ac.le.brisskit/brisskit.php
rm -r ${extensions_dir}/uk.ac.le.brisskit/info.xml
rm -r ${extensions_dir}/uk.ac.le.brisskit/LICENSE.txt

rm -r ${extensions_dir}/uk.ac.le.brisskit/build
rm -r ${extensions_dir}/uk.ac.le.brisskit/CRM
rm -r ${extensions_dir}/uk.ac.le.brisskit/css
rm -r ${extensions_dir}/uk.ac.le.brisskit/old
rm -r ${extensions_dir}/uk.ac.le.brisskit/our_hooks
rm -r ${extensions_dir}/uk.ac.le.brisskit/sql
rm -r ${extensions_dir}/uk.ac.le.brisskit/templates
rm -r ${extensions_dir}/uk.ac.le.brisskit/tests
rm -r ${extensions_dir}/uk.ac.le.brisskit/xml


#####################################################################
# Copy templates and xml files
echo "Copying template extras and xml"
mkdir ${case_root}
cp -r procedures/civicases/. ${case_root}


echo "Copying files from civix_extensions/uk.ac.le.brisskit to ${extensions_dir}" 
cp -uvr civix_extensions/uk.ac.le.brisskit "${extensions_dir}"
echo 


echo "Changing to extensions directory"
pushd civix_extensions/uk.ac.le.brisskit
echo 


echo "Diff ..."
diff -r . "${extensions_dir}/uk.ac.le.brisskit"
echo 


echo "Changing to patches directory"
pushd ../../patches/
echo 

#
# patch to include brisskit-specific settings (currently constants) from brisskit_civicrm.settings.php
#
# --forward (ignore if already patched)
# --reject-file=- (do not create reject file)
#
sudo patch --forward --reject-file=- "${drupalcore}/sites/default/civicrm.settings.php" civicrm.settings.php.patch
cp brisskit_civicrm.settings.php "${drupalcore}/sites/default/"


echo "Changing back to original directory"
popd
popd
popd
pwd



