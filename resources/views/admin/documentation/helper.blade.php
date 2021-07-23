## <i class="fas fa-cog mr-1"></i> Helper class
-------------

The Helper class exists here <kbd>/app/Classes/Helper.php</kbd> and gives the developer a list of handy common methods to speed
up development.

Helper uses the <kbd>\App\Classes</kbd> namespace.

-------------
### Things Helper can do for you

&nbsp;

#### Custom configuration: create and use config within your application
-------------

Within <kbd>/app/Config/</kbd> you can create custom config files that return a key value array that can be accessed anywhere within
the application. Helper can get these values, and even modify them on the fly if necessary.

Example of a config file:

<kbd>&lt;?php<br />
&nbsp;&nbsp;&nbsp;&nbsp;// /app/Config/MyCustomConfig.php<br />
&nbsp;&nbsp;&nbsp;&nbsp;return [<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'SomeKey' => 'Some value',<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'SomeOtherKey' => SomeOtherFunction('with custom data')<br />
&nbsp;&nbsp;&nbsp;&nbsp;];<br /></kbd>

Now you can call the entire array or retrieve a single value from a key using the following method:

// Retrieve the entire array

<kbd>Helper::GetConfig('MyCustomConfig');</kbd>

// Retrieve a single key

<kbd>Helper::GetConfig('MyCustomConfig', 'SomeKey');</kbd>

As soon as GetConfig() is called to retrieve a config file it is stored statically within so it no longer needs to import the
file, meaning any keys can be pulled from it as many times as needed without unecessary importing.

&nbsp;

#### Helpful methods
-------------

Properize a word: Turn a name like "Alan" into "Alan's". If the word already ends in an "s" then nothing will change as expected.

<kbd>Helper::properize('Alan')</kbd>

Returns Carbon::now(), but has the added feature of checking $_GET['forcedate'] to adapt the date for testing or potentially other reasons.

<kbd>Helper::getDate()</kbd>

Checks if $_COOKIE['laravel_cookie_consent'] is set and equal to true, returns false otherwise.

<kbd>Helper::checkCookieConsent()</kbd>

Checks recapcha response for Google ReCapcha, note that the Helper file must be edited to add the 'secret' for your application.

<kbd>reCaptchaCheck($postedRecaptchaResponse)</kbd>