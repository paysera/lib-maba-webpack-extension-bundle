# lib-maba-webpack-extension-bundle

This Bundle allows developer to hook into `maba/webpack-bundle` webpack.config.js dumping process and make changes
needed for developer environment.

## Why?

When working with `maba/webpack-bundle` aliases, you can often end up in situation where neither IDE (PHPStorm) or Eslint
gets happy when you import something like this:
```js
import { StaticTextFormGroup } from '@app/js/common/components/StaticText';
import { CheckboxFieldFormGroup } from '@app/js/common/components/CheckboxField';
```
1. Eslint yells on you about `import/no-unresolved`;
1. IDE (PHPStorm) does not helps you also - no navigation to imported module (ctrl + click);

This is because you run your project from `Docker` (or any other) container, paths in `webpack.config.js` are valid only from container
but not from host, so IDE (PHPStorm) fails to recognize paths in `webpack.config.js`.

## Installation

```bash
composer require --dev paysera/lib-maba-webpack-extension-bundle
```

In your `AppKernel`:
```php
    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        // ...
        $bundles[] = new Paysera\Bundle\MabaWebpackExtensionBundle\PayseraMabaWebpackExtensionBundle();
    }
```

## Configuration

##### As this Bundle is only needed for development purposes, we suggest you to configure it only in `config_dev.yml`.

Bundle exposes these options:
```yaml
paysera_maba_webpack_extension:
    replace_paths:
        /home/app/src: "/home/me/Projects/my-project"
    replace_items:
        webpack_config_path: true
        alias: true
        manifest_path: true
        entry: false
    replaced_webpack_config_path: "%kernel.cache_dir%/webpack.config_%kernel.environment%.js"
```

* `replace_paths` - `array` of key-value paths to replace in `webpack.config.js`.
* `replace_items` - `array` of switches where to use `replace_paths` on.
* `replaced_webpack_config_path` - `string` where to put modified `webpack.config.js`.

## Recommended setup

In your `config_dev.yml`:
```yaml
paysera_maba_webpack_extension:
    replace_paths:
        /home/app/src: "/home/me/Projects/my-project"
```

Install `eslint-import-resolver-webpack` and configure your `.eslintrc` settings with:
```json
  "settings": {
    "import/resolver": {
      "webpack": {
        "config": "app/cache/dev/webpack.config_dev.js"
      }
    }
  }
```
With these lines you'll be able to get rid of `import/no-unresolved` message.

### IDE config

Just change IDE's `webpack.config.js` to `app/cache/dev/webpack.config_dev.js`
