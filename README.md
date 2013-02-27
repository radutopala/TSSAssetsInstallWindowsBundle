TSSAssetsInstallWindowsBundle
===================

Creates assets:install symlinks in Windows with mklink

Installation instructions:

- Easiest way to install is via composer, add those lines to ```./composer.json```:
    
    
      ```
      "require": {
        ...
        "tss/assets-install-windows-bundle": "dev-master"
      }
```

 
  and then run ```composer.phar install```

- Then enable the bundle in ```./app/AppKernel.php```:
    
    ```
    public function registerBundles()
    {
        $bundles = array(
                ...
                new TSS\AssetsInstallWindowsBundle\TSSAssetsInstallWindowsBundle(),
            );
    }
```

- You can now create assets symlinks in Windows >= Vista:

    ```app/console assets:install:windows```

Enjoy :)

PS: Thanks @Dilibau for the tip