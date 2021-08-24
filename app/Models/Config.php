<?php

namespace App\Models;

use App\Classes\TableField;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Config extends Model
{

    /* SETUP
    ---------------------------*/
    // Uncomment to use soft deletes (Must use $table->softDeletes(); in migration)
    //use SoftDeletes;

    // DB real table name
    public $table = 'config';

    // Table alias
    public static $alias = 'configuration';

    // Add fields that should not be mass asigned to $guarded
    protected $guarded = [];

    
    /* BROWSING
    ---------------------------*/
    // Allows admins to browse and search rows for this model
    public static $adminCanBrowse = true;

    // Fields that display while browsing
    public static $tableFieldsBrowse = ['_key', '_value'];

    // Specify a field and direction for this model's rows while browsing eg ['field_name', 'asc']
    public static $orderBy = ['_key', 'asc'];

    // HTML text information to be displayed for this model at the top of the browsing page
    public static $information = 'Set up admin customizable configuration within your application. These values can be get and set with Helper::getDBConfig($key) and Helper::setDBConfig($key, $value). If the key does not exist then setDBConfig will create it.';
    
    
    /* CREATION / EDITING
    ---------------------------*/
    // Allows admins to edit
    public static $adminCanEdit = true;

    // A list of fields and their options, examples below
    public static $tableFieldsEdit = [
        '_key'         => ['type' => 'text', 'validation' => 'required|between:1,100', 'help' => 'Key for config item'],
        '_value'       => ['type' => 'textarea', 'help' => 'Value for config item']
    ];
}