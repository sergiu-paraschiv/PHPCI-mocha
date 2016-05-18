# PHPCI-mocha

### Mocha reporter for [PHPCI](https://www.phptesting.org/)


Add this to `composer.json`:

```
composer require sergiu-paraschiv/phpci-mocha
```

Then the task to `phpci.yml`:
```
\SergiuParaschiv\PHPCI\Plugin\Mocha:
    directory: "frontend"
    command: "npm run -s test:ci"
```

Npm should run with the `-s` flag.

Mocha should run with the `-f json` flag.

`test:ci` in `package.json` should be `"mocha --reporter mocha-tap-reporter --recursive app/test"`

To get output in the Information tab you'll need to copy the `public` folder into your PHPCI installation's `public` folder.
