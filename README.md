# Simple Symfony test application to add url.

Symfony: 6.2

PHP: 8.1

## How to Run in Local

1. Environment

    Copy `.env` to `.env.local` and change `DATABASE_URL`.

2. Migrate

    ```
    symfony console doctrine:migrations:migrate
    ```

3. Run server

    ```
    symfony server:start
    ```

    Go to http://localhost:8000.
    Type url to add.
    And click 'Add' button.
