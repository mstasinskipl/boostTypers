Boost Typers Galleries import</br>

1. Please run `composer install`
2. Please provide database access into `.env`
3. Please run `php artisan migrate`,

User register & login
1. Please run `php artisan passport:client`
2. Please run `php artisan passport:keys`
3. Routes for login and register user by API `api/register` and `api/login`
4. You should use headers like:
    <ul>
    <li>
        Accept: application/json
    </li>
    </ul>
    
Galleries:
1. To start import galleries use this command: `php artisan galleries:import`
2. Remember if you want to have access to galleries by API you should use header like:
    
    <ul>
    <li>
        Authorization: Bearer: [YOUR-ACCESS-TOKEN]
    </li>
    </ul>
4. You can list routes for API galleries by  this command: `php artisa route:list`        
    
