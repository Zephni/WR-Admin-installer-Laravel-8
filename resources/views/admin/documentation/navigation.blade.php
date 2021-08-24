## <i class="fas fa-sitemap mr-1"></i> Navigation
-------------

To modify the navigation panel, open and edit the <kbd>/app/Config/AdminNavigation.php</kbd> file

-------------
### Editing AdminNavigation.php

This file must return a key value array that follows this pattern:

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

By using all of the above tools it allows you to create a very rubust navigation and still allows for dynamic content with PHP and Laravel commands.