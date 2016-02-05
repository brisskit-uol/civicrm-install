pushd `dirname "$0"`


source ./procedures/bin/install-config.sh
source ./procedures/bin/set-install-paths.sh


extensions_dir="${drupalcore}/sites/${drupalsite}/files/civicrm/custom_ext"

if [ ! -d "${extensions_dir}" ]
then
  mkdir -p "${extensions_dir}"
fi

echo ${civicrminstall}

CRMroot="${drupalcore}/sites/all/modules//civicrm/CRM"
origCRMroot="${civicrmroot}/installs/${civicrminstall}/civicrm/CRM"

echo "Setting patched files to original versions"
sudo cp -v "${origCRMroot}/Case/BAO/Case.php"   "${CRMroot}/Case/BAO/Case.php"
sudo cp -v "${origCRMroot}/Core/DAO.php"        "${CRMroot}/Core/DAO.php"
sudo cp -v "${origCRMroot}/Core/I18n.php"       "${CRMroot}/Core/I18n.php"
echo 

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

# Use our view for cases rather than the real table

casephp="${CRMroot}/Case/BAO/Case.php"


perl -p -i -e 's/civicrm_case"/civicrm_brisskit_case"/g'    "${casephp}"
perl -p -i -e 's/civicrm_case\./civicrm_brisskit_case\./g'  "${casephp}"
perl -p -i -e 's/civicrm_case /civicrm_brisskit_case /g'    "${casephp}"
perl -p -i -e 's/civicrm_case\n/civicrm_brisskit_case\n/g'  "${casephp}"

#
# patch to include brisskit-specific settings (currently constants) from brisskit_civicrm.settings.php
#
# --forward (ignore if already patched)
# --reject-file=- (do not create reject file)
#
sudo patch --forward --reject-file=- "${drupalcore}/sites/default/civicrm.settings.php" civicrm.settings.php.patch
cp brisskit_civicrm.settings.php "${drupalcore}/sites/default/"

#
# patch to include brisskit-specific settings (set mysql variable) from brisskit_DAO.php
#
sudo patch --forward --reject-file=- "${CRMroot}/Core/DAO.php" DAO.php.patch
cp brisskit_DAO.php "${CRMroot}/Core/"

#
# patch to include brisskit-specific settings (load our replacement for ts() from brisskit_I18n.php
#
sudo patch --forward --reject-file=- "${CRMroot}/Core/I18n.php" I18n.php.patch
cp brisskit_I18n.php "${CRMroot}/Core/"

echo "Changing to patches files directory"
pushd files/
echo 

echo "Changing back to original directory"
popd
popd
popd
pwd

popd

#####################################################################
# Copy templates and xml files
echo "Copying template extras and xml"
mkdir ${case_root}
cp -r procedures/civicases/. ${case_root}

