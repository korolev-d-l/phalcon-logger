# Logger

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

`psql -h <host> -d <database> -U <user_name> -p <port> -a -w -f data/final.sql`

Or run migration (use phalcon devtools):
`phalcon migration run`

Two methods are available:

POST `/api/logs`

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

Requirements
------------

* PHP 7.0 and up
* Phalcon **3.2.0**
* Composer

License
-------

This project is open-sourced software licensed under the MIT License.

See the LICENSE file for more information.