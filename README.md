# Task Separator

App separates tasks from the input JSON file to the output files.

## Installation

In the app directory, use the [Composer](https://getcomposer.org) to install app components:

```bash
composer install
```

## Usage

Go to the app directory. For separate tasks from the file use command:

```bash
bin/task_separator <path_to_input_file.json>
```

The input file must be in the following format:

```json
[
  {
    "number":<id>,
    "description":"<task description>",
    "dueDate":"<date formatted to YYYY-MM-DD hh:mm:ss>",
    "phone":"<phone number>"
  },
  ...
]
```

App produces two or three output files:
- __inspections.json__ - inspection tasks. File format:

```json
[
  {
    "description":"<task description>",
    "type": "przegląd",
    "dueDate": "<date formatted to YYYY-MM-DD>"|null,
    "status":"zaplanowano|nowy",
    "comments":"",
    "phone":"<phone number>",
    "createdAt":"<creation date formatted to YYYY-MM-DD hh:mm:ss>",
    "weekOfYear": <week of year>|null
  },
  ...
]
```

- __accidents.json__ - accident tasks. File format:

```json
[
  {
    "description":"<task description>",
    "type": "zgłoszenie awarii",
    "dueDate": "<date formatted to YYYY-MM-DD>"|null,
    "status":"termin|nowy",
    "comments":"",
    "phone":"<phone number>",
    "createdAt":"<creation date formatted to YYYY-MM-DD hh:mm:ss>",
    "priority":"krytyczny|wysoki|normalny"
  },
  ...
]
```

- __errors.json__ - tasks with incorrect data (optional). File format is the as the input file.

For help screen please use:

```bash
bin/task_separator --help
```

## Tests

In the app directory tests are run with command:

```bash
bin/phpunit
```

## License
[MIT](./LICENSE)
