## <i class="fas fa-cog mr-1"></i> Commands
-------------

Artisan commands are often used when developing a Laravel application, you may wish to open some of these up to the administrator
user so they can run certain administrative tasks, or even custom made Artisan commands.

-------------
### Creating a new command

To create a new command use the Artisan command make:command and pass a camel case class name for it eg:

<kbd>php artisan make:command InspirationalQuote</kbd>

You will now find your new command file InspirationalQuote.php in /app/Console/Commands

Open this and modify the $signature property to whatever you wish to call the command by, eg:

<kbd>protected $signature = 'command:inspirationalquote';</kbd>

If you wish to have parameters passed you can specify them like so:

<kbd>protected $signature = 'command:inspirationalquote {amount} {ordered_by}';</kbd>

For more information see: [https://laravel.com/docs/8.x/artisan](https://laravel.com/docs/8.x/artisan)

-------------
### Allowing administrative users to access commands

Once logged in to this admin section, by default a link to "Commands" is in the left hand column. To modify the
navigation links see [/admin/documentation/navigation](/admin/documentation/navigation)

Here there will be a list of commands that have been allowed within the \App\Config\UserRunnableCommands.php array.
By default it looks like this:

<kbd>return [<br />
&nbsp;&nbsp;&nbsp;&nbsp;'command:&#42;',<br />
&nbsp;&nbsp;&nbsp;&nbsp;'route:&#42;'<br />
];<br /></kbd>

This works by checking every Artisan command against each pattern, it accepts an asterisk as a wild so be default as
show above all signatures begining with 'command:' or 'route:' will be shown and available to run as the admin.

Once the user clicks "Run command" it will start "working" and disable the button until it has finished and returned and displayed it's output.