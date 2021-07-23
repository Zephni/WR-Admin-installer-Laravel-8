## <i class="fas fa-database mr-1"></i> Tables &amp; Migrations
-------------

Creating &amp; modifying tables can be made much easier and managable with migrations, following this design pattern can save headaches in the future.
For full documentation see [https://laravel.com/docs/8.x/migrations](https://laravel.com/docs/8.x/migrations)

-------------
### Creating a new migration for a table

To create a new migration use the artisan command make:migration with the standard format alias as create_*table_name*_table eg:

<kbd>php artisan make:migration create_articles_table</kbd>

In the above example, this would create the following file, but with the current date and time stamp in the filename:

<kbd>/database/migrations/2021_07_10_160423_create_articles_table.php</kbd>

-------------
### Setting up the table

Now is the best time to think forward and decide which fields will be included within this table. Note that there is an
up() and a down() method, and both use the Schema object to either create or drop the table respectively.

Within the up()'s Schema::create() blueprint abstract function we want to add all of the fields that we will need, by default
it looks like this:

<kbd>Schema::create('articles', function (Blueprint $table) {<br />
&nbsp;&nbsp;&nbsp;&nbsp;$table->id();<br />
&nbsp;&nbsp;&nbsp;&nbsp;$table->timestamps();<br />
});<br /></kbd>

Like above, there are many $table->methods() that add fields to the table. It is best to keep these two defaults as these will
add the id, created_at and updated_at fields which may be very usefull.

Another option is to add soft delete functionality, this can be done by simply useing:

<kbd>$table->softDeletes();</kbd>

-------------
### Adding custom fields

Every table will have custom fields, an example of a custom string field named 'title' with limited characters:

<kbd>$table->string('title', 100);</kbd>

Some creation methods will only require a single parameter, this will be the name of the field. Others like string above take a second parameter,
in this case it is the number of characters that the VARCHAR field type can take.

Still, other methods like id() or softDeletes() mentioned above take no parameters atall, or atleast have default values preset. See the field
method list below for an overview of all available field types.

In some cases you may want to have null fields or default values for those fields, you can use the below extended methods as shown:

<kbd>$table->text('content')->default('Some default text')->nullable();</kbd>

As shown above the extended methods can be strung if needed. For more information see the official documentation [https://laravel.com/docs/8.x/migrations#column-modifiers](https://laravel.com/docs/8.x/migrations#column-modifiers)

-------------
### Modifying a table after it has already been created

To create a migration that adds, updates or removes a field.. or even renames or removes a table, use the same artisan command but change the naming format and specify the table to suit eg:

<kbd>php artisan make:migration add_author_to_articles_table --table=articles<br />
php artisan make:migration remove_author_from_articles_table --table=articles<br />
php artisan make:migration add_soft_deletes_to_articles_table --table=articles<br />
php artisan make:migration rename_articles_table --table=articles<br /></kbd>

Note that when adding a field or making any change after the inital creation migration you will need to add opposite to the change within the down() method eg:

<kbd>Schema::dropIfExists('users');</kbd>

-------------
### Running &amp; rolling back migrations

To run a migration simple run the following artisan command:

<kbd>php artisan migrate</kbd>

To roll back a migration run the below, you can modify the step to specify a number of migrations to 'step' back:

<kbd>php artisan migrate:rollback --step=1</kbd>

To reset all migrations (This will run the down() method on every migration in reverse order)

<kbd>php artisan migrate:reset</kbd>

-------------
### Field method list

Here is an example of some common field types to add to your new migration's Schema::create() method:

<kbd>
$table->bigIncrements('id');<br />
$table->bigInteger('votes');<br />
$table->binary('photo');<br />
$table->boolean('confirmed');<br />
$table->char('name', 100);<br />
$table->dateTimeTz('created_at', $precision = 0);<br />
$table->dateTime('created_at', $precision = 0);<br />
$table->date('created_at');<br />
$table->decimal('amount', $precision = 8, $scale = 2);<br />
$table->double('amount', 8, 2);<br />
$table->enum('difficulty', ['easy', 'hard']);<br />
$table->float('amount', 8, 2);<br />
$table->foreignId('user_id');<br />
$table->geometryCollection('positions');<br />
$table->geometry('positions');<br />
$table->id();<br />
$table->increments('id');<br />
$table->integer('votes');<br />
$table->ipAddress('visitor');<br />
$table->json('options');<br />
$table->jsonb('options');<br />
$table->lineString('positions');<br />
$table->longText('description');<br />
$table->macAddress('device');<br />
$table->mediumIncrements('id');<br />
$table->mediumInteger('votes');<br />
$table->mediumText('description');<br />
$table->morphs('taggable');<br />
$table->multiLineString('positions');<br />
$table->multiPoint('positions');<br />
$table->multiPolygon('positions');<br />
$table->nullableTimestamps(0);<br />
$table->nullableMorphs('taggable');<br />
$table->nullableUuidMorphs('taggable');<br />
$table->point('position');<br />
$table->polygon('position');<br />
$table->rememberToken();<br />
$table->set('flavors', ['strawberry', 'vanilla']);<br />
$table->smallIncrements('id');<br />
$table->smallInteger('votes');<br />
$table->softDeletesTz($column = 'deleted_at', $precision = 0);<br />
$table->softDeletes($column = 'deleted_at', $precision = 0);<br />
$table->string('name', 100);<br />
$table->text('description');<br />
$table->timeTz('sunrise', $precision = 0);<br />
$table->time('sunrise', $precision = 0);<br />
$table->timestampTz('added_at', $precision = 0);<br />
$table->timestamp('added_at', $precision = 0);<br />
$table->timestampsTz($precision = 0);<br />
$table->timestamps($precision = 0);<br />
$table->tinyIncrements('id');<br />
$table->tinyInteger('votes');<br />
$table->tinyText('notes');<br />
$table->unsignedBigInteger('votes');<br />
$table->unsignedDecimal('amount', $precision = 8, $scale = 2);<br />
$table->unsignedInteger('votes');<br />
$table->unsignedMediumInteger('votes');<br />
$table->unsignedSmallInteger('votes');<br />
$table->unsignedTinyInteger('votes');<br />
$table->uuidMorphs('taggable');<br />
$table->uuid('id');<br />
$table->year('birth_year');<br />
</kbd>