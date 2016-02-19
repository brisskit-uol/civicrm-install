#=======================================================================#
# Procedures and Artifacts for installing the Civicrm web application   #
#=======================================================================#

There are a number of possible approaches to using this project.

(1) A maven zipped artifact can be produced and used (see below)
(2) The git version of the project can be used
    Both of the above use the civicrm_install.sh script in the bin directory.
(3) Some of the distinctive puppet artifacts may be archived in the
    puppet-artifacts directory.

But this is not be the whole story...
  (a) It is assumed that Mysql has been installed locally
  (b) There are other pre-requisites installed using appt-get
      (For details, read the civicrm_install.sh script in the bin directory)
  (c) There is a wget into sourceforge to acquire the specific version of civicrm.
      This needs addressing. The patching needs to be taken into account.
      Plus the frequency of updating.

#======================================================================#
# Using Maven:                                                         #
# Inspect the POM and the production-bin.xml in the assembly directory #
#======================================================================#
To build a local zip artifact, the default install invocation is sufficient...
mvn clean install

To build and deploy to the remote BRISSKit repo, you need the requisite
credentials in your maven settings.xml file.
Then the following invocation should suffice:
mvn clean deploy
