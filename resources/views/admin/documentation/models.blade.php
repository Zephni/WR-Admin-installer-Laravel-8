## <i class="fas fa-cubes mr-1"></i> Models
-------------

### Boolean option properties

Models that can be created / edited and browsed must have the 2 following static properties set to true:

<kbd>$adminCanBrowse = true</kbd>

<kbd>$adminCanEdit = true;</kbd>

-------------
### Browsing and basic model setup properties

With each editable Model you must set the table property to the related database table_name, eg:

<kbd>$table = 'table_name';</kbd>

To set an alias for the table for user display use:

<kbd>$alias = 'Model name';</kbd>

To determine what fields show when browsing use an array (note you can override the display name with a key):

<kbd>$tableFieldsBrowse = ['title', 'publish_date', 'Custom name' => 'field_name', ...];</kbd>

To add a custom field to show while browsing you can call local method which will return HTML to display in the row:

<kbd>$tableFieldsBrowse = ['title', 'publish_date', 'Is live' => 'method::IsLive'];</kbd>

To order by a specific field use:

<kbd>$orderBy = ['publish_date', 'desc'];</kbd>

To allow certain field values to display raw HTML on the browse page use:

<kbd>$displayAsHTMLFields = ['field_name', ...]</kbd>

To display information to the user regarding the table you can choose to add some information:

<kbd>$information = 'Some info about the Model...';</kbd>

-------------
### Adding &amp; Customising admin editable fields

To set which fields can be edited, fill the $tableFieldsEdit property:

<kbd>public static $tableFieldsEdit = [];</kbd>

Below are some examples of $tableFieldsEdit fields, currently these are used for both creating *and* editing:

<kbd>
    <table>
        <tr>
            <td>'author'</td>
            <td>&nbsp;=&gt; ['type' => 'select',  'options' => 'join Author name'];</td>
        </tr>
        <tr>
            <td>'category'</td>
            <td>&nbsp;=&gt; ['type' => 'select',  'options' => 'join Category title'];</td>
        </tr>
        <tr>
            <td>'title'</td>
            <td>&nbsp;=&gt; ['type' => 'text', 'validation' => 'required|between:5,70', 'help' => 'Title should be between 5 and 70 characters'];</td>
        </tr>
        <tr>
            <td>'content'</td>
            <td>&nbsp;=&gt; ['type' => 'wysiwyg'];</td>
        </tr>
        <tr>
            <td>'image'</td>
            <td>&nbsp;=&gt; ['type' => 'image', 'previewType' => 'landscape', 'path' => 'news', 'validation' => 'required|image|mimes:jpeg,jpg|max:256';</td>
        </tr>
        <tr>
            <td>'publish_date'</td>
            <td>&nbsp;=&gt; ['type' => 'datetime'];</td>
        </tr>
        <tr>
            <td>'tags'</td>
            <td>&nbsp;=&gt; ['type' => 'tags'];</td>
        </tr>
        <tr>
            <td>'live'</td>
            <td>&nbsp;=&gt; ['type' => 'select', 'options' => ['1' => 'Yes', '0' => 'No'], 'default' => '1;</td>
        </tr>
    </table>
</kbd>

-------------
### Hooks

Using the below hooks you can run a custom set of instructions before the model is created, edited or deleted. It will be called as the last action before saving or performing the operation.

<kbd>public function createHook(){ /* $this->field = 'example'; */ }</kbd>

<kbd>public function editHook(){ /* Perform an extra action... */ }</kbd>

<kbd>public function deleteHook(){ /* Perform an extra action... */ }</kbd>

-------------
### Relationships

The $relationships property must contain a list of any related tables by the real table name

<kbd>public static $relationships = ['author'];</kbd>

When adding a relationship, you must define the model this "belongsTo", or one of the other predfined Laravel relationship methods [https://laravel.com/docs/8.x/eloquent-relationships#defining-relationships](https://laravel.com/docs/8.x/eloquent-relationships#defining-relationships)

<kbd>public function author()<br />
{<br />
    &nbsp;&nbsp;&nbsp;&nbsp;return $this->belongsTo('App\Author', 'author');<br />
}</kbd>

*Note that in this case within the Author model you would likely want the inverse, hasMany() method.*

-------------
### Methods and custom methods

Include a "view" link for the frontend of a given instance of a model:

<kbd>public function getFrontendURL(){<br />
&nbsp;&nbsp;&nbsp;&nbsp;return route('news', ['id' => $id]);<br />
}</kbd>

Get image method (needs to be customised for paths), handy for using on the front end

<kbd>
public function getImage()<br />
{<br />
&nbsp;&nbsp;&nbsp;&nbsp;if(env('APP_ENV') == 'local')<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return 'https://domain.com/images/news/'.$this->image;<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;return ($this->image != '' && file_exists(public_path('images/news/'.$this->image)))<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;? '/images/news/'.$this->image<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '/images/assets/placeholder-landscape.png';<br />
}
</kbd>