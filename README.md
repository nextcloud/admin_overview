# Admin overview 🔮
Enhanced insights in your Nextcloud server!

<div style="text-align: center; width: 100%;"><img src="img/app-dark.svg" alt="" width="300" /></div>

## Building the app

The app can be built by using the provided Makefile by running:

    make

This requires the following things to be present:
* make
* which
* tar: for building the archive
* curl: used if phpunit and composer are not installed to fetch them from the web
* npm: for building and testing everything JS, only required if a package.json is placed inside the **js/** folder

The make command will install or update Composer and NPM dependencies and also run `npm run build`.

### Building manually
You can also build it manually, first install the dependencies:
```
npm ci
```

Then build the frontend by running
```
npm run build
```

## Publish to App Store

First get an account for the [App Store](http://apps.nextcloud.com/) then run:

    make && make appstore

The archive is located in build/artifacts/appstore and can then be uploaded to the App Store.

## Running tests
You can use the provided Makefile to run all tests by using:

    make test
