# Sanctum user commands

These local Artisan commands manage users and API tokens for the Laravel Sanctum-protected internal API.

## Prerequisites

Run the commands from the Laravel application root. The database must be configured and migrated, including the `users` and `personal_access_tokens` tables.

## Create a user and token

```sh
php artisan sanctum:create-user "n8n Lead Review" "n8n@irskostudy.cz" "use-a-strong-password"
```

The command creates the user and prints a new plaintext Sanctum token. Store the token securely, for example in n8n credentials, and send it with requests:

```http
Authorization: Bearer YOUR_TOKEN
```

The password is a command-line argument and may be saved in shell history. Use an appropriate secure environment when running this command.

## List users

```sh
php artisan sanctum:list-users
```

The output includes user details and token metadata such as token ID, token name, and last-used time. It does not print token values. Sanctum stores token secrets hashed, so existing plaintext tokens cannot be recovered.

## Regenerate a selected user’s token

```sh
php artisan sanctum:regenerate-token n8n@irskostudy.cz
```

The command:

1. Finds the user by email.
2. Revokes all existing Sanctum tokens for that user.
3. Creates one new `api-token` token.
4. Prints the new plaintext token.

Any integrations using the old tokens stop working immediately. Update the integration credentials with the newly printed token.

If no user matches the supplied email, the command exits with an error and does not create a token.

## Application files

- `app/Console/Commands/CreateSanctumUser.php`
- `app/Console/Commands/ListSanctumUsers.php`
- `app/Console/Commands/RegenerateSanctumToken.php`
- `app/Models/User.php`
