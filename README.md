 

## GymApp  

Gym membership system with private coach and lesson management.  Developed using Laravel 12 and FilamentPHP 


Features: 

* Plan management
* Membership with Trial and Full
* Create the users with multiple-roles
* Attendece reporting
* Dashboard


## Installation

1. Clone the repository:
    ```bash
    git clone <repository-url>
    cd gymapp
    ```

2. Install dependencies:
    ```bash
    composer install
    npm install
    ```

3. Copy environment file:
    ```bash
    cp .env.example .env
    ```

4. Generate application key:
    ```bash
    php artisan key:generate
    ```

5. Configure your database in `.env` file

6. Run migrations:
    ```bash
    php artisan migrate
    ```

7. Build assets:
    ```bash
    npm run build
    ```

8. Start the development server:
    ```bash
    php artisan serve
    ```

Access the application at `http://localhost:8000`


## License

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE](LICENSE) file for details.