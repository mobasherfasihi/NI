# Native Instruments

Native Instruments Code Challenge

# Technologies

-   PHP - 8.0.x
-   Laravel - 8.x
-   VueJS - 3.1.x

# Development

Development environment has been setup using sail for more information about sail please refer to this documentation. https://laravel.com/docs/8.x/sail

-   Clone the project.

-   Run `composer install`

-   To run server please run.
    ```
    ./sail up -d
    ```
-   To down the server run:
    ```
    ./sail down
    ```
-   To Start VueJs.
    ```
    ./sail npm run watch.
    ```
-   To list the docker container.
    ```
    ./sail ps
    ```

# Production

-   Clone the project.
-   Make sure that new git tag created on each deployment.
-   To build a docker image please run:
    ```
    make build
    ```
-   To down and up the image please run:
    ```
    make down && make up
    ```

# License

This is a private repository
