A humhub docker image with support for the Engine project OAuth portal SSO

How it works?
------------

Two files are included - the config file - common.php - and the authclient implementation. They are copied onto the installation. The finish.sh is provided to automate the installation, it creates the empty humhub database, enables clean urls and automatic logon. You need to run it after starting the docker (just docker exec /finish.sh)

On site instructions
--------------------
Disable all setup options apart from friendships.
Use localhost for mysql host, db -> humhub, root -> user, nopasswd
