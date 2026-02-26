# CentralAuth OAuth Test Application

A minimal OAuth 2.0 Authorization Code flow test using the custom `CentralAuth` provider (extends `league/oauth2-client`).

This example uses the [CentralAuth provider](https://github.com/CentralAuth/CentralAuth-PHP-library) for the [PHP OAuth client library](https://github.com/thephpleague/oauth2-client).

**Live Demo**: [https://php-example.centralauth.com](https://php-example.centralauth.com)

For complete CentralAuth configuration and API documentation, visit: **[https://docs.centralauth.com](https://docs.centralauth.com)**

## Features
- Authorization Code + PKCE
- Custom CentralAuth provider with POST userinfo retrieval
- Session-based login + profile

## Setup
1. Install dependencies:
   - PHP 8.3+
   - Composer
   - Nginx
2. Install libraries:
```
composer install
```
3. Create your environment file:
```
cp .env.example .env
```
4. Edit `.env` with your real credentials and endpoints.
5. Set your redirect URI to `http://localhost/api/auth/callback`.
6. Configure Nginx with the provided `nginx.conf` (see nginx configuration for URL rewriting).
7. Visit: `http://localhost/index.php`

Note: The clean auth endpoints are provided via Nginx rewrite rules in `nginx.conf`. These rewrite `/api/auth/login`, `/api/auth/logout`, `/api/auth/callback`, and `/profile` to their respective page files.

### Configure Whitelisted Domains

In your CentralAuth dashboard, configure the allowed domains for your application:

- **For localhost**: Enable the **"Allow localhost"** setting during development (no additional configuration needed)
- **For production**: Add your domain to the **whitelist domains** list. You only need to register the base domain (e.g., `example.com`), not the full callback URL path

The SDK will automatically handle the `/api/auth/callback` path for all whitelisted domains.

## Usage Flow

1. **Home Page** (`/`): Click "Login with CentralAuth"
2. **Redirect to CentralAuth**: You'll be redirected to the CentralAuth login page
3. **Authenticate**: Enter your credentials or sign up
4. **Callback**: CentralAuth redirects back to `/api/auth/callback`
5. **Profile Page** (`/profile`): View your authenticated user information
6. **Logout**: Click "Logout" to clear your session and return home

## Environment Variables (.env)
| Variable                         | Description                                                                |
| -------------------------------- | -------------------------------------------------------------------------- |
| OAUTH_CLIENT_ID                  | CentralAuth Organization ID                                                |
| OAUTH_CLIENT_SECRET              | CentralAuth Secret key                                                     |
| OAUTH_REDIRECT_URI               | Redirect URI of your application (e.g. http://localhost/api/auth/callback) |
| OAUTH_AUTHORIZATION_URL          | Authorization/ Login endpoint (e.g. https://centralauth.com/login)         |
| OAUTH_TOKEN_URL                  | Token / verification endpoint (e.g. https://centralauth.com/api/v1/verify) |
| OAUTH_RESOURCE_OWNER_DETAILS_URL | User info endpoint (e.g. https://centralauth.com/api/v1/userinfo)          |

## Custom Provider Usage Example
```php
use CentralAuth\OAuth2\Client\Provider\CentralAuth; // From centralauth/oauth2-centralauth package
$provider = new CentralAuth([
  'clientId' => $_ENV['OAUTH_CLIENT_ID'],
  'clientSecret' => $_ENV['OAUTH_CLIENT_SECRET'],
  'redirectUri' => $_ENV['OAUTH_REDIRECT_URI'],
  'authorization_url' => $_ENV['OAUTH_AUTHORIZATION_URL'],
  'token_url' => $_ENV['OAUTH_TOKEN_URL'],
  'resource_owner_details_url' => $_ENV['OAUTH_RESOURCE_OWNER_DETAILS_URL']
]);
```

## Troubleshooting

- **"Authentication failed" error**: Check your `OAUTH_CLIENT_ID` and `OAUTH_CLIENT_SECRET`
- **Session not persisting**: Make sure cookies are enabled in your browser
- **Not logged in after callback**: Verify your `OAUTH_AUTHORIZATION_URL` and `OAUTH_TOKEN_URL` are correct
- **Redirect URI mismatch error**: Ensure your `OAUTH_REDIRECT_URI` in `.env` matches what's configured in CentralAuth and matches your whitelisted domain

## Security Notes
- Do not commit `.env` (ensure `.gitignore` contains it)
- Use production secrets through real environment configuration (Nginx environment variables, secrets management, etc.)

## License
MIT
