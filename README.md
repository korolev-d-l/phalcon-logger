# Logger

[![Latest Version](https://img.shields.io/packagist/v/Chameleon-m/phalcon-logger.svg)][:packagist:]
[![Software License](https://img.shields.io/github/license/mashape/apistatus.svg)][:license:]
[![Total Downloads](https://img.shields.io/packagist/dt/Chameleon-m/phalcon-logger.svg)][:packagist:]
[![Build Status](https://secure.travis-ci.org/Chameleon-m/phalcon-logger.svg?branch=master)][:ci:]


How to install
--------------
### Using Composer (*recommended*)

Best way to install skeleton would be Composer, if you didn't install it

Run code in the terminal:

```bash
composer create-project Chameleon-m/phalcon-logger /path/to/install
```

### Using Git

First you need to clone the project, update vendors:

```bash
git clone https://github.com/Chameleon-m/phalcon-logger.git ./project
cd project
composer update
```

### DB

For PostgreSQL:

`psql -h <host> -d <database> -U <user_name> -p <port> -a -w -f tests/_data/dump.sql`

Or run migration (use phalcon devtools):
`phalcon migration run`

Two methods are available:

POST `/api/logs`

POST `/api/logs?queue=1` - push in queue (for save need run task `php cli.php logs queue -v -t
`)

The data in it is passed to json. For example:

POST `curl -X POST -d '{"entity":"Event","entityId":1000,"date":"2017-12-12 12:12:12","userId":3,"action":"create","diff":{"before":"test1","after":"test2"}}' http://phalcon-logger.dev/api/logs`

```
{
    "entity": "Event",
    "entityId": 1000,
    "date": "2017-12-12 12:12:12",
    "userId": 3,
    "action": "create",
    "diff": {
        "before": "test1",
        "after": "test2"
    }
}
```

The date is passed in the Postgresql timestamp format.

GET `/api/logs`

Possible filters for the query:

* from - paired filter from to. Indicates the date on which the filtering starts.
* to - paired filter with from. Indicates the date the filtering ends.
* entity - the name of the entity.
* entityId - array with entity id.
* action - the name of the action.
* userId - array with user id.

The response is as follows:

GET `curl -X GET http://phalcon-logger.dev/api/logs?page=3`

```
{
    "items": [
        {
            "id": 1,
            "entity": "Event",
            "entityId": 1000,
            "date": "2017-12-12 12:12:12",
            "userId": 3,
            "action": "create",
            "diff": {
                "before": "test1",
                "after": "test2"
            }
        },
        {...},
        {...},
    ],
    "first": 1,
    "before": 2,
    "current": 3,
    "last": 8,
    "next": 4,
    "total_pages": 8,
    "total_items": 72,
    "limit": 10
}
```
Attribute - Description

items -	The set of records to be displayed at the current page\
current - The current page\
before - The previous page to the current one\
next - The next page to the current one\
last - The last page in the set of records\
total_pages - The number of pages\
total_items - The number of items in the source data\

### Testing
First you need to re-generate base classes for test all suites:

`vendor/bin/codecept build`

Once the database is created and base clases re-generated, run the tests on a terminal:

`vendor/bin/codecept run`

or for detailed output:

`vendor/bin/codecept run --debug`

Requirements
------------

* PHP 7.0 and up
* Phalcon **3.2.0**
* Composer

License
-------

This project is open-sourced software licensed under the MIT License.

See the LICENSE file for more information.

[:packagist:]: https://packagist.org/packages/Chameleon-m/phalcon-logger
[:ci:]: http://travis-ci.org/Chameleon-m/phalcon-logger
[:license:]: https://github.com/Chameleon-m/phalcon-logger/blob/master/LICENSE.txt