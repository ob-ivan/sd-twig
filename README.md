Configuration
-------------
```yaml
twig:
    loader:
        class: App\Twig\MyLoader
        path: templates
        rootDir: ''                     # relative to root dir provided by container
        extra:
            - '.twig'
        paths:
            __main__: ''
            templates: twig/templates
    cache:
        class: App\Twig\MyCache
        path: var/cache/twig            # relative to root dir provided by container
    extensions:
        - Twig_Extensions_Extension_Text
        - App\Twig\MyExtension          # dependencies will be injected
    extension_factories:
        - App\Twig\MyExtensionFactory
        - SD\Twig\Extension\ProfilerExtensionFactory
```
