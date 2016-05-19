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
    command: "npm run test:ci"
    data_offset: 2
```

Mocha should run with the `-f json` flag.

`data_offset` is the number of lines at the top of the output to skip before parsing the response.

`test:ci` in `package.json` should be `"mocha --reporter mocha-tap-reporter --recursive app/test"`

To get output in the Information tab you'll need to copy the `public` folder into your PHPCI installation's `public` folder.
