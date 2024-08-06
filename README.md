# How to start the project

1. Clone the repository into a new directory using the following command:
   ```
   git clone --branch main https://github.com/zainulabdain-burfat/secret-message.git
   ```
2. Navigate to the project's root directory:
    ```
    cd secret-message
    ```
3. Create a `.env` file by copying the `.env.example` file with the following command:
    ```
    cp .env.example .env
    ```
4. Run Docker Compose to start the containers in the background:
    ```
    docker-compose up -d
    ```
5. Access the application container by running the following command:
    ```
    docker exec -it message-app /bin/bash
    ```
6. Once inside the application container, run the following commands to install dependencies, generate keys, and run database migrations:
    ```
    composer install
    php artisan key:generate
    php artisan storage:link
    php artisan migrate
    npm install
    npm run build
    ```
7. To start the Supervisor process manager, run the following commands inside the application container:
    ```
    service supervisor stop
    service supervisor start
    supervisorctl start all
    ```
8. You can now access the site at `localhost`



# Usage Instructions
## Register/Login:
The project uses Laravel's default Breeze authentication with Blade. You need to register or log in to use the application.

## User Setup:
Ensure that you have at least two users in the system to send and receive messages.

## Sending a Message:

Navigate to the dashboard.
Fill out the form to send a message, including selecting a recipient, entering the message text, optionally setting it to be read once, and setting an expiry date and time.
Upon sending the message, an email will be sent to the recipient with the message identifier and the encryption key.
## Receiving a Message:

The recipient will receive an email via Mailhog, which will include the message identifier and the encryption key.
Use these details in the "Retrieve Message" form on the dashboard to decrypt and read the message.
# Additional Notes
## Outgoing Emails:
Mailhog is used for outgoing emails. You can access the Mailhog interface at http://localhost:8025 to view the emails sent by the application.

## Encryption Details:
Messages are encrypted using OpenSSL (AES-256-CBC). Each message has a unique encryption key, which is shared with the recipient via email.

## Expiration and Read Once:
Messages can be set to expire after a certain period or be read only once. If a message is read once, it will be automatically deleted after being viewed.
