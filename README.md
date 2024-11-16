# Roomvu Service Challenge

This service hands the user and transaction management for the Roomvu project.

The User entity has the following properties:

- Id
- Name
- Email
- Credit

Each User can have several transactions per day. A Transaction entity has the following properties:

- Id
- Amount
- Date

## Requirements

To run this project, you will need:

- Docker and Docker Compose installed

## Installation

First, clone the repository:

git clone <repository_url>

cd <repository_directory> docker-compose up -d

## Usage

### Populating the User List

You can populate the user list by running the `app:create-bulk-users` command. For example, to create 10 users:

`docker-compose exec app php console.php app:create-bulk-users 10`

### Bulk Creating Transactions

After creating users, you can create multiple transactions by running the `app:create-bulk-transactions` command. For
example, to create 10 transactions:

`docker-compose exec app php console.php app:create-bulk-transactions 10`

### Reports

#### Total Transaction Amount For Each User Per Day

To get a report of the total transaction amount for a specific user on a specific day, you need to run
the `app:get-user-daily-transactions` command.

Here is an example:

`docker-compose exec app php console.php app:get-user-daily-transactions 3 "2024-10-10"`

In the command above, `3` is the ID of the user and `"2024-10-10"` is the date you wish to generate the report for. This
would yield a total transaction amount for user ID 3 on October 10, 2024.

#### Total Transactions Amount For All Users Per Day

To get a report of the total transaction amount for all users on a specific day, you need to execute
the `app:get-all-users-daily-transactions` command.

Here is an example:

`docker-compose exec app php console.php app:get-all-users-daily-transactions "2024-10-11"`

In this command, `"2024-10-11"` is the date for which you wish to generate the report. This command will yield the total
transaction amount for all users on October 11, 2024.

## Tests
To ensure the codebase is functioning as expected, our project comes bundled with PHPUnit for writing unit tests. Run the tests with the following command:
Run the tests with the following command:

`vendor/bin/phpunit`


## License

Licensing details for the project are not specified at the moment and are under consideration.
