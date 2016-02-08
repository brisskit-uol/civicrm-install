echo "Setting patched files to original versions"
sudo cp -v "${origCRMroot}/Case/BAO/Case.php"   "${CRMroot}/Case/BAO/Case.php"
sudo cp -v "${origCRMroot}/Core/DAO.php"        "${CRMroot}/Core/DAO.php"
sudo cp -v "${origCRMroot}/Core/I18n.php"       "${CRMroot}/Core/I18n.php"

sudo rm "${CRMroot}/Core/brisskit_DAO.php"
echo

