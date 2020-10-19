# Vue.js Webpacked as a library setup for use in exixting PHP applications

## Intro

This repository is intended to be a starting point for introducing a working front-end [Vue.js] setup, exported as a library, into an existing PHP project.
Includes Babel transpiling, Webpack bundling and hot reload dev server.

It is an expansion of the repository created in this article, by Michał Męciński: [Using Vue.js components in PHP applications][post]

It comes with a docker-compose file whose image has apache, php and node / npm pre-installed. 


## Setup

1. Clone this repository

 ```bash

    git clone https://github.com/djleven/vue-in-legacy-php.git

```

2. Launch docker

 ```bash
    cd vue-in-legacy-php
    docker-compose up -d
 ```
 
3. Install dependencies

You can install dependencies (and run the scripts) from inside the container if you don't want to install node
 
 ```bash
    docker-compose exec legacy-php-vue bash
    cd ../vue-app
    npm install
 ```
 
 Otherwise simply
  ```bash
    cd vue-app
    npm install
  ```

## How it works - Usage

The main differences with the Michał Męciński setup are that the wepback output is configured:
 - to export your VueJS code as a library 
 - for multiple entry points: Each file added to the entries folder is dynamically exported by wepback.

You can then reference these by calling `{VUE_LIBRARY_NAME}{entry_file_name}{method_exported}`

You change the the name `VUE_LIBRARY_NAME` in .env file, but the default example would be:
```html
<script>MyVueLib.demo.hello('#app')</script>
```


Wepback does it's magic in two modes, dev and production.

### Development

In development mode, the js and css files are served by webpack-dev-server with the hot module replacement
on a separate port of your localhost, http://localhost:8080 by default. 
             
This port can be changed editing the appropriate .env files

To start a dev mode
```bash
    cd vue-app
    npm run dev
```

You can all see the files served at [http://localhost:8080/webpack-dev-server]

### Production

In production mode, the js and css files are generated into the public path of your assets folder (configurable via .env file) in a minified form

To generate minified files for production:

```bash
cd vue-app
npm run build
```

### Asset management

The output path URI's are common between these two modes to make switching easier.

Example outputs path for demo.js:
- Dev mode = localhost:8080/assets/js/demo.js
- Production: /assets/js/demo.min.js?[hash]


There is a php helper class, `AssetManager` which tries to make the switch as easy as possible:

You instantiate it by providing the file name (ex: demo) and whether you're in production mode (ex: false)
```php
$asset_manager = new VueInLegacyPhp\Templating\AssetManager('demo', false);
```
You can then get your files links like this:

```php
<link rel="stylesheet" href="<?php echo $asset_manager->css_file_path?>" />
<script src="<?php echo $asset_manager->js_file_path;?>"></script>
```

Then when you're finished with dev mode, you just have remove the `false` argument to get your prod links and run the build script to generate the files.

## Workflow

### Adding to the library

So as mentioned before, the format of the library is `{VUE_LIBRARY_NAME}{entry_file_name}{method_exported}`. 
The file name serves as the entry point for collection of methods it exports. 
Each new such collection is to be added in the entries folder.
The existing example is demo.js

```js
// ...
import Vue from 'vue';
import VueResource from 'vue-resource';
import HelloWorld from '../components/HelloWorld.vue';

Vue.use(VueResource);

Vue.component('hello', HelloWorld);


export function hello(element) {
	console.log('hello');
	return new Vue({
		el: element,
		render: h => h(HelloWorld)
	});
}
```

So the above is called like so in your php / html file:

```html
<script>MyVueLib.demo.hello('#app')</script>
```

If you only want to export one method then you can use ```export default name_of_method``` instead

Then you would reference it as `{VUE_LIBRARY_NAME}{entry_file_name}default`

Ex: ```html
    <script>MyVueLib.my_entry_filename.default(arg1, arg2)</script>
    ```

### New components

Create [Single File Components] in `vue-app/src/components/` and add them to your entry -exported- methods like so:

```js
// ...
import MyComponent from './components/MyComponent.vue';

// ...
Vue.component('my-component', MyComponent);

// ...
```


### Communication with PHP

You can pass data derived from PHP in your instantiation of the Vue methods, since this is taking place on the PHP side.
In our simple example we only passed the selector of the element to be replaced, but you can pass anything really.

After the instantiation, you can use AJAX to exchange data with the PHP server.
 
Using [vue-resource] will make this process easier.


[post]: https://codeburst.io/using-vue-js-components-in-php-applications-e5bfde8763bc
[Vue.js]: https://vuejs.org
[http://localhost:8080/webpack-dev-server]:http://localhost:8080/webpack-dev-server
[Single File Components]: https://vuejs.org/v2/guide/single-file-components.html
[vue-resource]: https://github.com/pagekit/vue-resource
