## <i class="fas fa-sitemap mr-1"></i> Navigation
-------------

To modify the navigation panel, first open and edit the /app/Http/Controllers/AdminController.php controller

-------------
### What to edit

Within AdminController.php, the first preset property is a static one named $navigation:

<kbd>public static $navigation = null;</kbd>

This is not to be edited, but rather set within the private buildNavigation() method below it:

<kbd>private function buildNavigation(){<br />
&nbsp;&nbsp;&nbsp;&nbsp;AdminController::$navigation = [<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...
</kbd>

buildNavigation() is called at the begining of every view method within the admin section, this practice should be continued with any new views that require navigation.
We are working within a method so we can call other objects, methods and properties into our navigation.

The AdminController::$navigation static property must be set within the buildNavigation() method, this must be a key value array that follows this pattern:

<kbd>'Dashboard' => ['icon' => 'fa-gem', 'url' => route('admin')]</kbd>

The key is the text that the user see's for the menu option, the value is an array that can take three 'parameters':

icon:

<kbd>'icon' => 'fa-gem'</kbd>

The value of icon must be a free font awesome class name, find them here [https://fontawesome.com/v5.0/icons?d=gallery&p=2](https://fontawesome.com/v5.0/icons?d=gallery&p=2)

url:

<kbd>'url' => route('admin')</kbd>

The value of url must be a valid href, it is probably best to use a route defined name to confirm it is a working url.

An optional paramter is to include a submenu, which looks something like this:

<kbd>'submenu' => [<br />
&nbsp;&nbsp;&nbsp;&nbsp;'Create new' => ['icon' => 'fa-plus', 'url' => route('admin-create', ['table' => 'pages'])],<br />
&nbsp;&nbsp;&nbsp;&nbsp;'Browse'     => ['icon' => 'fa-eye',  'url' => route('admin-browse', ['table' => 'pages'])]<br />
]<br /></kbd>

As you can see the items within the submenu array follows the same structure as a standard menu item using just the 'icon' and 'url' parameters.

To add a group break within the navigation panel just add a text value without a key, this will be rendered to the user eg:

<kbd>'Content Management',</kbd>

By using all of the above tools it allows you to create a very rubust navigation and still allows for dynamic content with PHP and Laravel commands, see the example below to get an idea how this may be used.

-------------
### Quick example of navigation method and contents

Below is a practical example of how navigation may be set up within AdminController.php:

<kbd>
private function buildNavigation(){<br />
&nbsp;&nbsp;&nbsp;&nbsp;AdminController::$navigation = [<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Dashboard'             => ['icon' => 'fa-gem',      'url' => route('admin')],<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Documentation'         => ['icon' => 'fa-question', 'url' => route('admin-documentation')],<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Content Management',<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Pages' => [<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'icon'           => 'fa-file-alt',        'url' => route('admin-browse', ['table' => 'pages']),<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'submenu' => [<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Create new' => ['icon' => 'fa-plus', 'url' => route('admin-create', ['table' => 'pages'])],<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Browse'     => ['icon' => 'fa-eye',  'url' => route('admin-browse', ['table' => 'pages'])]<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\App\Classes\Helper::properize(Auth::user()->name).' profile',<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Manage account'       => ['icon' => 'fa-user-edit', 'url' => route('admin-manage-account')],<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;    ];<br />
}<br />
</kbd>