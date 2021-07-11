 # WR Admin installer 

##### LARAVEL 8 

----------------------------------
#### 1. Create Laravel project and install Breeze:

Optional: Below is a long command that does all of step 1's commands in single line, just change the siteAlias varaible below with whatever you need to call your new Laravel project, note that sometimes it gets stuck for about 1 minute, just let it do it's thing:

    siteAlias=mynewsite.com; composer create-project laravel/laravel $siteAlias && cd $siteAlias && composer require laravel/breeze --dev && php artisan breeze:install && npm install && npm run dev

Or you can do each step 1 at a time

    composer create-project laravel/laravel mynewsite.com
    cd mynewsite.com
    composer require laravel/breeze --dev
    php artisan breeze:install
    npm install
    npm run dev

----------------------------------
#### 2. Create a database, and setup .env with connection details

Create a database locally or on a server and modify the route .env file with the new connection details.

----------------------------------
#### 3. Copy premade content

 - Copy everything from this directory other than this README.md file into the new Laravel site route.
 - Delete '/resources/views/dashboard.blade.php'

----------------------------------
#### 4. Migrate database

    php artisan migrate

(This includes two new fields added to the create_users_table migration)

----------------------------------
#### 5. Register a user, run:

Start tinker mode

    php artisan tinker
    
Temporarily create the createUser function

    function createUser($name, $email, $password, $isAdmin = false){$user = new App\Models\User();$user->name = $name;$user->email = $email;$user->password = Hash::make($password);$user->is_admin = (!$isAdmin) ? 0 : 1;return $user->save();}

Run the createUser function and replace the string values appropriately, the 4th parameter is a boolean that determines whether the new user is an admin

    createUser('NAME', 'EMAIL_ADDRESS', 'PASSWORD', 1);

----------------------------------
#### 6. Now may be a good time to 'git init'

    git init && git add -A && git commit -m "Initialised laravel 8 with WR Admin installation"

#### 7: OPTIONAL - Will live have /public in a different location?

If you plan to have the public folder in a different location like '/../public_html' we can set up
our dev environment with the same structure. They can both be different if need be and either have
different sets of files on each (the ones below), or set up conditions to check the .env APP_ENV
setting to switch on the fly, but it can simplify things to just keep it all the same structure.

Go to /App/Providers/AppServiceProvider.php and add this to register()

    // set the public path to this directory
    $this->app->bind('path.public', function() {
        return base_path().'/../public_html';
    });
 
Now open /server.php and replace all instances from '/public' to '/../public_html'

    '/public'
    '/../public_html' // Of course change this depending on your environment

Finally within the public/index.php or public_html/index.php file find and edit the
$DirToLaravelApplication variable to point to the laravel installation, effectively the reverse
of the above:

    $DirToLaravelApplication = '/../'; // This is for a default Laravel installation where public is within the same directory
    $DirToLaravelApplication = '/../mynewsite.com'; // This is if Laravel has a seperate directory one level below public

NOTE: All of this can be done just before deployment, but may aswell do straight away so you are working with the same structure.

----------------------------------
#### 8. Main set up, now it's time to:
  - Make migrations for new tables
  - Copy the DBModelTemplate.php Model and modify for created tables
  - Edit AdminController.php and set up the navigation
  - Read the built in documentation for detailed help