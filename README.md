# Laravel RBAC
Laravel Role Based Access Control for Laravel 5+.

## Installation

Require via composer:

```
composer require farhoudi/laravel-rbac:^1
```

Register service provider to the `providers` array in `config/app.php`

```php
Farhoudi\Rbac\RbacServiceProvider::class,
```

Publish migration files

```
$ php artisan vendor:publish --provider="Farhoudi\Rbac\RbacServiceProvider"
```

Run migrations

```
$ php artisan migrate
```

Add RBAC middleware to your `app/Http/Kernel.php`

```php
protected $routeMiddleware = [
    'permission' => 'Farhoudi\Rbac\Middleware\HasPermission::class',
    'role' => 'Farhoudi\Rbac\Middleware\HasRole::class',
];
```

Add Rbac trait to your `User` model

```php
use Farhoudi\Rbac\Rbac;
	
class User extends Authenticatable
{
    use Rbac;
    ...
	    
}
```

## Usage

### Roles

#### Create role

```php
$adminRole = new Role;
$adminRole->name = 'Administrator';
$adminRole->slug = 'administrator';
$adminRole->description = 'System Administrator';
$adminRole->save();

$editorRole = new Role;
$editorRole->name = 'Editor';
$editorRole->slug = 'editor';
$editorRole->description = 'Editor';
$editorRole->save();
```

#### Assign role to user
	
```php
$user = User::find(1);
$user->roles()->attach($adminRole->id);
```

you can also assign multiple roles at once

```php
$user->roles()->attach([$adminRole->id, $editorRole->id]);
```

#### Revoke role from user

```php
$user->roles()->detach($adminRole->id);
```

you can also revoke multiple roles at once

```php
$user->roles()->detach([$adminRole->id, $editorRole->id]);
```

#### Sync roles

```php
$user->roles()->sync([$editorRole->id]);
```

Any role already assigned to user will be revoked if you don't pass its id to sync method.

### Permissions

#### Create permission

```php
$createUser = new Permission;
$createUser->name = 'Create user';
$createUser->slug = 'user.create';
$createUser->description = 'Permission to create user';
$createUser->save();

$updateUser = new Permission;
$updateUser->name = 'Update user';
$updateUser->slug = 'user.update';
$updateUser->description = 'Permission to update user';
$updateUser->save();
```

#### Assign permission to role

```php
$adminRole = Role::find(1);
$adminRole->permissions()->attach($createUser->id);
```

you can also assign multiple permissions at once

```php
$adminRole->permissions()->attach([$createUser->id, $updateUser->id]);
```

#### Revoke permission from role

```php
$adminRole->permissions()->detach($createUser->id);
```

you can also revoke multiple permissions at once

```php
$adminRole->permissions()->detach([$createUser->id, $updateUser->id]);
```

#### Sync permissions

```php
$adminRole->permissions()->sync([$updateUser->id]);
```

Any permission already assigned to role will be revoked if you don't pass its id to sync method.

### Check user roles/permissions

Roles and permissions can be checked on `User` instance using `hasRole` and `canDo` methods.

```php
$isAdmin = Auth::user()->hasRole('administrator'); // pass role slug as parameter
$isAdminOrEditor = Auth::user()->hasRole('administrator|editor'); // using OR operator
$canUpdateUser = Auth::user()->canDo('update.user'); // pass permission slug as parameter
$canUpdateOrCreateUser = Auth::user()->canDo('update.user|create.user'); // using OR operator
```

### Protect routes

Laravel RBAC provides middleware to protect single route and route groups. Middleware expects 2 comma separated params: 
- **is** or **can** as first param - what to check (role/permission)
- role/permission slug as second param

```php
Route::get('/backend', [
    'uses' => 'BackendController@index',
    'middleware' => ['auth', 'rbac:is,administrator']
]);
Route::get('/backend', [
    'uses' => 'BackendController@index',
    'middleware' => ['auth', 'rbac:is,administrator|editor']
]);
Route::get('/dashboard', [
    'uses' => 'DashboardController@index',
    'middleware' => ['auth', 'rbac:can,view.dashboard']
]);
Route::get('/dashboard', [
    'uses' => 'DashboardController@index',
    'middleware' => ['auth', 'rbac:can,view.dashboard|view.statistics']
]);
```

### Blade directive

Laravel RBAC provides two Blade directives to check if user has role/permission assigned.

Check for role

```
@ifUserIs('administrator')
    // show admin content here
@else
    // sorry
@endif

@ifUserIs('administrator|editor')
    // show editor content here
@else
    // sorry
@endif
```

Check for permission

```
@ifUserCan('delete.user')
    // show delete button
@endif

@ifUserCan('delete.user|manage.user')
    // show delete button
@endif
```

## License

Laravel RBAC is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
