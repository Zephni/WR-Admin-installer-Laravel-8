 # WR Admin installer 

##### LARAVEL 8 

----------------------------------
#### 1. Create Laravel project with:

    composer create-project laravel/laravel domain.com

----------------------------------
#### 2. Follow the steps here in order:
    composer require laravel/breeze --dev
    php artisan breeze:install
    npm install
    npm run dev

Optional: If you like all these can be run on a single line, just change the two instances of 'domain.com' below with the site alias, note that sometimes it gets stuck for about 1 minute, just let it do it's thing:

    composer create-project laravel/laravel domain.com && cd domain.com && composer require laravel/breeze --dev && php artisan breeze:install && npm install && npm run dev

----------------------------------
#### 3. Create a database, and setup .env with connection details

Create a database locally or a server and modify the route .env file with the new details

----------------------------------
#### 4. Copy premade content

Copy everything from this ADMIN INSTALLATION directory other than this text file into new site
Delete '/resources/views/dashboard.blade.php'

----------------------------------
#### 5. Migrate database
php artisan migrate
(This includes two new fields added to the create_users_table migration)

----------------------------------
#### 6. Register a user, run:

Start tinker mode

    php artisan tinker
    
Create the createUser function

    function createUser($name, $email, $password, $isAdmin = false){$user = new App\Models\User();$user->name = $name;$user->email = $email;$user->password = Hash::make($password);$user->is_admin = (!$isAdmin) ? 0 : 1;return $user->save();}

Run the createUser function, but replace the values appropriately, the 4th parameter is a boolean that determines whether the new user is an admin

    createUser('NAME', 'EMAIL_ADDRESS', 'PASSWORD', 1);

----------------------------------
#### 7. Now may be a good time to 'git init'

    git init && git add -A && git commit -m "Initialised laravel 8 with WR Admin installation"

#### 8: OPTIONAL - Will live have /public in a different location?

If you plan to have the public folder in a different location like "/../public_html"
Go to /App/Providers/AppServiceProvider.php and add this to register()

    // set the public path to this directory
    $this->app->bind('path.public', function() {
        return base_path().'/../public_html';
    });
 
Now open /server.php and replace all instances from /public to /../public_html

    '/public'
    '/../public_html'

Also within the public/index.php or public_html/index.php file find and edit the
$DirToLaravelApplication turnery condition with the live domain of the website, plus update the
second value which is the live path of the laravel directory. Further instruction within index.php

----------------------------------
#### 9. Main set up, now it's time to:
  - Make migrations for new tables
  - Copy the DBModelTemplate.php Model and modify for created tables
  - Edit AdminController.php and set up the navigation
  - Read the built in documentation for detailed help
NOTE: This can be done just before deployment, but may aswell do straight away