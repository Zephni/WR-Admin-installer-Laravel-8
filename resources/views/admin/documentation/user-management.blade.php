## <i class="fas fa-user mr-1"></i> User management
-------------

Use "tinker" mode before continuing with any of the following steps:

<kbd>php artisan tinker</kbd>

-------------
### Create a new user

Copy and paste this temporary function below in tinker mode:

<kbd>function createUser($name, $email, $password, $isAdmin = false){<br />
&nbsp;&nbsp;&nbsp;&nbsp;$user = new App\Models\User();<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$user->name = $name;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$user->email = $email;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$user->password = Hash::make($password);<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$user->is_admin = (!$isAdmin) ? 0 : 1;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;return $user->save();<br />
}<br /></kbd>

Finally run the temporary function with your custom paramters:

<kbd>createUser('NAME', 'EMAIL_ADDRESS', 'PASSWORD', 1);</kbd>

-------------
### Get a user for either modifying or removing

Store user in a temporary variable using either the 'email', 'id' or 'name' field

<kbd>$user = App\User::where('email', 'EMAIL_ADDRESS')->first();</kbd>

-------------
### Modify user

After storing the $user variable, you can modify any of the following fields:

<kbd>$user->name = 'NEW_NAME';</kbd><br />
<kbd>$user->email = 'NEW_EMAIL_ADDRESS';</kbd><br />
<kbd>$user->password = Hash::make('NEW_PASSWORD');</kbd>

Finally make sure to save the user model

<kbd>$user->save();</kbd>

-------------
### Delete user

To delete a user first store the user into a variable, and then run the delete() method:

<kbd>$user = App\User::where('email', 'EMAIL_ADDRESS')->first();<br />
$user->delete();</kbd>