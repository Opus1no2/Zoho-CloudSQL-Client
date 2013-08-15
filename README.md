Zoho CloudSQL Client
====================
 
A simple interface for using Zoho's CloudSQL API

Installation
------------

Installation is conveniently provided via Composer.

To get started, install composer in your project:

```sh
$ curl -s https://getcomposer.org/installer | php
```

Next, add a composer.json file containing the following:

```js
}
    "require": {
        "zoho/cloudsql-client": "dev-master"
    }
}
```

Finally, install!

```sh
$ php composer.phar install
```

Usage
-----

Using the Query class is easy:

``` php
<?php
require_once 'Query.php';

try {
    $query = new Zoho_CloudDb_Query();
    $result = $query->setDb('your_db_table')
        ->setQuery('SELECT "column1" from "employee_id_table" where "column1" = 1234')
        ->run();
    print_r(json_decode($result));
} catch (Exception $e) {
    echo $e->getMessage();
}

```

License
--------
MIT
