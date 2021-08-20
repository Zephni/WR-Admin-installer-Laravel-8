<?php

namespace App\Models;

use App\Classes\TableField;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DBModelTemplate extends Model
{

    /* SETUP
    ---------------------------*/
    // Uncomment to use soft deletes (Must use $table->softDeletes(); in migration)
    //use SoftDeletes;

    // DB real table name
    protected $table = 'table_name';

    // Table alias
    // public static $alias = 'Table name';

    // Add fields that should not be mass asigned to $guarded
    protected $guarded = [];


    /* RELATIONSHIPS
    ---------------------------*/
    // Note: I feel that this should be able to be grabbed automatically when called, but for now listing them here
    // List of this model's local related fields, eg:
    //public static $relationships = ['author'];

    // Relationship methods (first parameter is the associated model class, the second is this table's field name), eg:
    //public function author()
    //{
    //    return $this->belongsTo(\App\Models\Author::class, 'author');
    //}

    
    /* BROWSING
    ---------------------------*/
    // Allows admins to browse and search rows for this model
    public static $adminCanBrowse = true;

    // Fields that display while browsing
    public static $tableFieldsBrowse = ['title', 'author', 'publish_date'];

    // Specify a field and direction for this model's rows while browsing eg ['field_name', 'asc']
    public static $orderBy = ['publish_date', 'desc'];

    // An array of fields which are allowed to be displayed as HTML when browsing
    public static $displayAsHTMLFields = [];

    // HTML text information to be displayed for this model at the top of the browsing page
    public static $information = '';
    
    
    /* CREATION / EDITING
    ---------------------------*/
    // Allows admins to edit
    public static $adminCanEdit = true;

    // A list of fields and their options, examples below
    public static $tableFieldsEdit = [
        //'author'        => ['type' => 'select',  'options' => 'join Author name'],
        //'title'         => ['type' => 'text', 'validation' => 'required|between:5,70', 'help' => 'Title should be between 5 and 70 characters'],
        //'content'       => ['type' => 'wysiwyg'],
        //'image'         => ['type' => 'image', 'previewType' => 'landscape', 'path' => 'news', 'validation' => 'required|image|mimes:jpeg,jpg|max:256', 'help' => 'Image should be a valid JPG, 700 x 400 in size, no larger than 256kb' /*, 'alert' => '<b>New!</b> Please make sure to save images as jpg\'s from now on before uploading <i class="fas fa-thumbs-up"></i>'*/],
        //'-html'         => '<hr /><h2>Some example HTML</h2>',
        //'tags'          => ['type' => 'tags'],
        //'publish_date'  => ['type' => 'datetime'],
        //'live'          => ['type' => 'select', 'options' => ['1' => 'Yes', '0' => 'No'], 'default' => '1'],
    ];


    /* METHODS
    ---------------------------*/
    // getImage()
    //public function getImage()
    //{
    //    if(env('APP_ENV') == 'local')
    //        return 'https://domain.com/images/news/'.$this->image;
    //
    //    return ($this->image != '' && file_exists(public_path('images/news/'.$this->image)))
    //        ? '/images/news/'.$this->image
    //        : '/images/assets/placeholder-landscape.png';
    //}

    // getFrontendURL()
    //public function getFrontendURL()
    //{
    //    return route('news-article', ['idslug' => $this->id.'-'.Str::slug($this->title)]);
    //}
}