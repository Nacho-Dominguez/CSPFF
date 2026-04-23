# Controller for a Plugin

1. In plugins, create A25_Plugin_TryKata.  The class should be empty,
but after the class declaration, add this code:

        set_include_path(
          ServerConfig::webRoot . '/custom/A25/Plugin/TryKata'
          . PATH_SEPARATOR
            . get_include_path()
         );
         
    (Note: it is possible that we will eventually make this directory added to the classpath in the future, in which case, this step will become unecessary.)
1. In plugins/, create a new folder: plugins/TryKata/.  Normally, this is the naming scheme we would use to put helper classes, such as this controller, for the class A25_Plugin_TryKata.  But that is unnecessary for our little practice here.  We are just creating the controller, not the Observer itself.
1. In plugins/TryKata/Controller/, create a [Controller](https://github.com/thomasalbright/acre/wiki/Controllers) for printing the surveys in the TryKata directory.  Make it simply output "Hello" to the screen.
1. In configurations/colorado/custom/A25/Plugin/ (on the virtual machine), create symbolic links to plugins/TryKata/ and to plugins/TryKata.php.  You must go into the directory to make the link, don't try to do it from /var/www/

        ln -s ../../../../../plugins/TryKata TryKata
        ln -s ../../../../../plugins/TryKata.php TryKata.php
1. Link to the colorado custom directory from /var/www/ so it is active
1. Try loading /administrator/try-kata in the browser. It should output "hello".
