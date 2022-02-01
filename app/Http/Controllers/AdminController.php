<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Classes\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    public static $navigation = null;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    public function viewSetup()
    {
        AdminController::$navigation = Helper::getConfig('AdminNavigation');
    }

    public function index()
    {
        $this->viewSetup();
        return view('admin/index', []);
    }

    public function commands()
    {
        $this->viewSetup();

        // Array we expect to be building, to look like: ['command:somecommand', 'command:anothercommand',...]
        $commandsArray = [];

        try
        {
            Artisan::call('list --raw');
            $commandList = Artisan::output();
            $seperatedCommandLines = explode("\n", $commandList);
            $seperatedCommandLines = array_filter($seperatedCommandLines);
            
            foreach($seperatedCommandLines as $command)
            {
                $keyValue = explode(' ', $command, 2);
                $keyValue[0] = trim($keyValue[0]);
                $keyValue[1] = trim($keyValue[1]);
                
                $beginsWithAllowedWildcard = self::checkWildcard($keyValue[0], \App\Classes\Helper::getConfig('UserRunnableCommands'));

                if(!$beginsWithAllowedWildcard)
                    continue;

                $commandsArray[$keyValue[0]] = $keyValue[1];
            }
        }
        catch(Exception $e)
        {
            die('Error listing commands: '.$e->getMessage());
        }

        return view('admin/commands',  [
            'commands' => $commandsArray
        ]);
    }

    public function commandRun(Request $request)
    {
        $command = $request->get('command');

        // Check that the command parameter is set
        if($command === null || $command === false)
            die('In AdminController->commandRun(), $request->get(\'command\') must be set.<br /><br />The post parameter \'command\' was not present.'); 

        // Check that command is allowed to be run by user otherwise a user could run anything :O
        if(!self::checkWildcard($command, \App\Classes\Helper::getConfig('UserRunnableCommands')))
           die('In AdminController->commandRun(), $request->get(\'command\') must be present in the /app/Config/UserRunnableCommands.php array"<br /><br />Attempted to run: '.$command);
        
        // Check that the command actually exists in the Artisan list of available commands
        if(!array_key_exists($command, Artisan::all()))
            die('In AdminController->commandRun(), $request->get(\'command\') does not exist as a valid Artisan command.<br /><br />Attempted to run: '.$command);

        // Call the command and get the output
        Artisan::call($command);
        $output = Artisan::output();
        $output = Str::replace("\n", "<br/>", $output);
        $output = Str::replace("  ", "&nbsp;&nbsp;", $output);
        
        return $output;
    }

    /**
     * browse
     *
     * @param string $table
     * @return view
     */
    public function browse($table, Request $request)
    {
        $this->viewSetup();
        
        $modelRef = self::getModelRef($table);
        
        if($modelRef == false || !isset($modelRef::$adminCanBrowse) || $modelRef::$adminCanBrowse != true)
            return back();

        if(isset($modelRef::$protected) && $modelRef::$protected == 'zephni' && auth()->user()->id != 1)
            return back();
        
        $orderBy = $modelRef::$orderBy ?? ['id', 'desc'];
        // Custom ordering
        if(request()->get('orderby')) $orderBy = [request()->get('orderby'), (request()->get('order') != 'asc') ? 'asc' : 'desc'];
        $collection = $modelRef::orderBy($orderBy[0], $orderBy[1])->where(function($collection) use($request, $modelRef){
            // Search user query on all editable fields
            if($request->query('q')){
                $userQuery = $request->query('q');
                $arrayKeys = array_keys($modelRef::$tableFieldsEdit);

                // Search fields
                for($i = 0; $i < count($arrayKeys); $i++){
                    $collection->orWhere($arrayKeys[$i], 'like', "%{$userQuery}%");
                }

                // Search relationships
                if(isset($modelRef::$relationships)){
                    foreach($modelRef::$relationships as $relationship){
                        $collection->orWhereHas($relationship, function($collectionRelation) use($userQuery){
                            $collectionRelation->where('name', 'like', "%{$userQuery}%");
                        });
                    }
                }
            }
        })->paginate(15);
        
        // Build table and join fields from Models' $tableFieldsBrowse list
        $tableFields = [];
        $joinFields = [];
        $selectFieldData = [];
        foreach($modelRef::$tableFieldsBrowse as $key => $field)
        {
            // If also present in $tableFieldsEdit then check for special, eg. joins
            if(array_key_exists($field, $modelRef::$tableFieldsEdit))
            {
                // Get fieldInfo eg. "title" => ["type" => "text", ...]
                $fieldInfo = $modelRef::$tableFieldsEdit[$field];

                // If select type
                if($fieldInfo['type'] == 'select')
                {
                    // If options are a fixed array
                    if(is_array($fieldInfo['options']))
                    {
                        $tableFields[$field] = self::PrettifyFieldName($field);
                        $selectFieldData[$field] = [];
                        foreach($fieldInfo['options'] as $k => $v)
                        {
                            $selectFieldData[$field][$k] = $v;
                        }
                    }
                    // If options are a relationship / join
                    else
                    {
                        // $split = join 'Modal' 'field' // eg. "join User name"
                        $split = explode(' ', $fieldInfo['options']);
                        $tableFields[$split[1]] = self::PrettifyFieldName($split[1]);
                        $joinFields[$split[1]] = $split[2];
                    }
                }
                // If not a select type
                else
                {
                    $tableFields[$field] = self::PrettifyFieldName($field);
                }
            }
            // If can't be found in $tableFieldsEdit list
            else
            {
                $tableFields[$field] = self::PrettifyFieldName($field);
            }

            // If key is set as string eg. "Short name" => "really_long_field_name" then overwrite
            if(is_string($key))
            {
                $tableFields[$field] = $key;
            }
        }

        // Display as HTML fields
        $displayAsHTMLFields = (isset($modelRef::$displayAsHTMLFields)) ? $modelRef::$displayAsHTMLFields : [];

        // Display as image fields
        $displayAsImageFields = $modelRef::$displayAsImageFields ?? [];

        // Special collection content (supporting methods for field values, will run on every instance within collection)
        // Add to $tableFieldsBrowse like so: 'Nice name' => 'method::MethodName'
        $methodFields = [];
        foreach($modelRef::$tableFieldsBrowse as $k => $v)
        {
            if(substr($v, 0, 8) === "method::"){
                $methodFields[$k] = $v;
                $displayAsHTMLFields[] = $k;

                foreach($collection as $item)
                {
                    // Check method exists and run on each instance
                    $method = explode('::', $v)[1];
                    if(method_exists($modelRef, $method)){
                        $item->$v = $item->$method();
                    }
                }
            }
        }

        $information = $modelRef::$information ?? '';
        
        return view('admin/browse',  [
            'modelRef'             => $modelRef,
            'tableName'            => $table,
            'tableAlias'           => $modelRef::$alias ?? self::PrettifyFieldName($table, true),
            'tableFields'          => $tableFields,
            'collection'           => $collection,
            'joinFields'           => $joinFields,
            'selectFieldData'      => $selectFieldData,
            'methodFields'         => $methodFields,
            'displayAsHTMLFields'  => $displayAsHTMLFields,
            'displayAsImageFields' => $displayAsImageFields,
            'information'          => $information
        ]);
    }

    /**
     * create
     *
     * @param string $table
     * @return view
     */
    public function create(string $table) : View
    {
        $this->viewSetup();

        $modelRef = self::getModelRef($table);

        if($modelRef == false || !isset($modelRef::$adminCanEdit) || $modelRef::$adminCanEdit != true)
            return back();
        
        if(isset($modelRef::$protected) && $modelRef::$protected == 'zephni' && auth()->user()->id != 1)
            return back();

        $tableFields = $this->buildTableFields($modelRef::$tableFieldsEdit);

        return view('admin/create', [
            'modelRef'      => $modelRef,
            'tableName'     => $table,
            'tableAlias'    => $modelRef::$alias ?? self::PrettifyFieldName($table, false),
            'tableFields'   => $tableFields
        ]);
    }

    /**
    * createAction
    *
    * @param Request $request
    * @return redirect
    */
    public function createAction(Request $request)
    {
        $this->viewSetup();

        $modelRef = $request->modelRef;
        $ufields = $this->getUDataFromRequest($request);

        // Validation
        $validation = [];
        foreach($modelRef::$tableFieldsEdit as $fieldKey => $fieldValue)
            if(array_key_exists('validation', $fieldValue))
                $validation['ufield_'.$fieldKey] = $fieldValue['validation'];
        
        $request->validate($validation);
        
        $newInstance = new $modelRef();
        foreach($ufields as $k => $v)
        {
            // Check for special
            if($modelRef::$tableFieldsEdit[$k]['type'] == 'image')
            {
                if(!isset($modelRef::$tableFieldsEdit[$k]['path']))
                    die('Image "path" not set in Model $modelRef::$tableFieldsEdit');

                $v = $this->imageUpload($request, 'ufield_'.$k, $modelRef::$tableFieldsEdit[$k]['path']);
            }

            $newInstance->$k = $v;
        }

        // Create hook
        if(method_exists($newInstance, 'createHook'))
            $newInstance->createHook();

        $newInstance->save();

        $table = $newInstance->getTable();

        // Update sitemap.xml
        //try{$response = \Illuminate\Support\Facades\Http::get('https://kbbfocus.com/sitemap/generate');}catch (\Exception $e) {}

        return redirect()->route('admin-edit', ['table' => $table, 'id' => $newInstance->id])->
            with('success', 'Successfully created '.strtolower($this->PrettifyFieldName($table)).' item.
            <a href="'.route('admin-browse', ['table' => $table]).'">See all '.$table.'</a>');
    }

    /**
    * edit
    *
    * @param string $table
    * @return view
    */
    public function edit(string $table, int $id) : View
    {
        $this->viewSetup();

        $modelRef = self::getModelRef($table);

        if($modelRef == false || !isset($modelRef::$adminCanEdit) || $modelRef::$adminCanEdit != true)
            return back();
        
        if(isset($modelRef::$protected) && $modelRef::$protected == 'zephni' && auth()->user()->id != 1)
            return back();

        $instanceToEdit = $modelRef::where('id', $id)->first();
        $tableFields = $this->buildTableFields($modelRef::$tableFieldsEdit, $instanceToEdit);

        return view('admin/edit', [
            'modelRef'      => $modelRef,
            'instance'      => $instanceToEdit,
            'tableName'     => $table,
            'tableAlias'    => $modelRef::$alias ?? self::PrettifyFieldName($table, false),
            'tableFields'   => $tableFields,
            'id'            => $id
        ]);
    }

    /**
    * editAction
    *
    * @param Request $request
    * @return redirect
    */
    public function editAction(Request $request)
    {
        $this->viewSetup();

        $modelRef = $request->modelRef;
        $ufields = $this->getUDataFromRequest($request);
        
        // Validation
        $validation = [];
        foreach($modelRef::$tableFieldsEdit as $k => $v)
        {
            if(array_key_exists('validation', $v))
            {
                if($v['type'] == 'image' && $request->file('ufield_'.$k) == null)
                    continue;

                $validation['ufield_'.$k] = $v['validation'];
            }
        }
        
        $request->validate($validation);
        
        $editInstance = $modelRef::where('id', $request->id)->first();
        foreach($ufields as $k => $v)
        {
            // Check for special
            if($modelRef::$tableFieldsEdit[$k]['type'] == 'image')
            {
                if($request->hasFile('ufield_'.$k))
                {
                    if(!isset($modelRef::$tableFieldsEdit[$k]['path']))
                        die('Image "path" not set in Model $modelRef::$tableFieldsEdit');

                    $v = $this->imageUpload($request, 'ufield_'.$k, $modelRef::$tableFieldsEdit[$k]['path']);
                    $removeOldImage = public_path('images/'.$modelRef::$tableFieldsEdit[$k]['path'].'/'.$editInstance->$k);
                    
                    if(is_file($removeOldImage))
                        unlink($removeOldImage);
                }
            }

            $editInstance->$k = $v;
        }

        // Edit hook
        if(method_exists($editInstance, 'editHook'))
            $editInstance->editHook();

        $editInstance->save();

        $table = $editInstance->getTable();

        // Update sitemap.xml
        //try{$response = \Illuminate\Support\Facades\Http::get('https://kbbfocus.com/sitemap/generate');}catch (\Exception $e) {}

        return redirect()->route('admin-edit', ['table' => $editInstance->getTable(), 'id' => $editInstance->id])->with(
            'success', 'Successfully modified '.strtolower($this->PrettifyFieldName($table)).' item.
                       <a href="'.route('admin-browse', ['table' => $table]).'">See all '.$table.'</a>'
        );
    }

    /**
    * deleteAction
    *
    * @param Request $request
    * @return redirect
    */
    public function deleteAction(Request $request)
    {
        $this->viewSetup();

        $modelRef = $request->modelRef;
        
        $deleteInstance = $modelRef::where('id', $request->deleteID);

        // Delete hook
        if(method_exists($deleteInstance, 'deleteHook'))
            $deleteInstance->deleteHook();

        $deleteInstance->delete(); // SOFT DELETE

        // FORCE DELETE (Rather than soft delete)
        // if(isset($deleteInstance->image)) { // Remove image if exists
        //     $removeOldImage = public_path('images/'.$modelRef::$tableFieldsEdit['image']['path'].'/'.$deleteInstance->image);
        //     if(is_file($removeOldImage)) unlink($removeOldImage);
        // }
        // $deleteInstance->forceDelete();
        // /FORCE DELETE

        // Update sitemap.xml
        try{$response = \Illuminate\Support\Facades\Http::get('https://kbbfocus.com/sitemap/generate');}catch (\Exception $e) {}

        $temp = (new $modelRef)->getTable();
        return redirect()->route('admin-browse', ['table' => $temp]);
    }

    /**
    * manageAccount 
    *
    * @param int $overideID
    * @return view
    */
    public function manageAccount ($overideID = null) : View
    {
        $this->viewSetup();

        $modelRef = self::getModelRef('User');
        $table = 'users';

        $user = User::where('id', Auth::user()->id)->first(); // VSCode error reporting bug fix, as custom functions complain with just Auth::user()
        $id = $overideID ?? $user->id;
        
        $instanceToEdit = $user;
        $instanceToEdit->image = $instanceToEdit->getProfileImage();
        
        $tableFields = $this->buildTableFields($modelRef::$userFieldsEdit, $instanceToEdit);

        return view('admin/manage-account', [
            'modelRef'      => $modelRef,
            'tableName'     => $table,
            'tableAlias'    => self::PrettifyFieldName($table, false),
            'tableFields'   => $tableFields,
            'id'            => $id
        ]);
    }

    /**
    * manageAccountAction
    *
    * @param Request $request
    * @return redirect
    */
    public function manageAccountAction(Request $request)
    {
        $this->viewSetup();

        $id = Auth::user()->id;
        $ufields = $this->getUDataFromRequest($request);

        // Remove password field if user did not type one
        if(isset($ufields['password']) && $ufields['password'] === '')
        {
            unset($ufields['password']);
            unset($request->password);
        }
        
        // Validation
        $validation = [];
        foreach(User::$userFieldsEdit as $k => $v)
        {
            if(array_key_exists('validation', $v))
            {
                if($v['type'] == 'image' && !$request->hasFile('ufield_'.$k))
                    continue;

                // Check still exists in ufields before validating
                if(isset($ufields[$k]))
                    $validation['ufield_'.$k] = $v['validation'];
            }
        }
        
        $request->validate($validation);
        
        $editInstance = User::where('id', $id)->first();
        foreach($ufields as $k => $v)
        {
            // Check for special
            // Image
            if(User::$userFieldsEdit[$k]['type'] == 'image')
            {
                if($request->hasFile('ufield_'.$k))
                {
                    if(!isset(User::$userFieldsEdit[$k]['path']))
                    {
                        die('Image "path" not set in Model $modelRef::$userFieldsEdit');
                    }

                    $v = $this->imageUpload($request, 'ufield_'.$k, User::$userFieldsEdit[$k]['path'], 'u'.$id.'_');

                    $removeOldImage = public_path('images/'.User::$userFieldsEdit[$k]['path'].'/'.$editInstance->$k);
                    if(is_file($removeOldImage))
                        unlink($removeOldImage);
                }
            }
            // If type is password (but not password confirmation) then handle password update
            else if(User::$userFieldsEdit[$k]['type'] == 'password' && (!isset(User::$userFieldsEdit[$k]['confirmation']) || User::$userFieldsEdit[$k]['confirmation'] != true))
            {
                $v = Hash::make($v);
            }
            // Continue on the following (Do not set in edit instance)
            else if(isset(User::$userFieldsEdit[$k]['confirmation']) && User::$userFieldsEdit[$k]['confirmation'] == true)
            {
                continue;
            }
            
            $editInstance->$k = $v;
        }

        $editInstance->save();

        return redirect()->route('admin-manage-account')->with('success', 'Successfully updated profile');;
    }

    public function documentation($subject = '')
    {
        $this->viewSetup();
        
        if($subject == '')
        {
            $content = view('/admin/documentation/index');
        }
        else
        {
            if(view()->exists('/admin/documentation/'.$subject))
                $content = view('/admin/documentation/'.$subject);
            else
                $content = view('/admin/documentation/404');
        }

        return view('/admin/documentation', [
            'subject' => $subject,
            'content' => $content
        ]);
    }

    /**
     * PrettifyFieldName
     *
     * @param string $tableName
     * @param ?bool $plural
     * @return string
     */
    public static function PrettifyFieldName(string $tableName, ?bool $plural = null) : string
    {
        $string = $tableName;
        $string = ucfirst($string);
        $string = str_replace('_', ' ', $string);

        if($plural !== null)
        {
            $string = ($plural) ? Str::of($string)->plural() : Str::of($string)->singular();
        }

        return (string)$string;
    }

    public static function getModelRef(string $table)
    {
        $modelRef = '\\App\\Models\\'.Str::studly(Str::singular($table));
        
        // If class doesn't exist just double check the 's' hasn't been removed
        if(!class_exists($modelRef))
            $modelRef = '\\App\\Models\\'.Str::studly($table);

        return (class_exists($modelRef)) ? $modelRef : false; 
    }

    private function getUDataFromRequest($request)
    {
        $ufields = [];
        foreach($request->all() as $k => $v)
        {
            if(substr($k, 0, 7) == 'ufield_')
                $ufields[substr($k, 7)] = ($v === null) ? "" : $v;
        }
        
        return $ufields;
    }

    /**
     * imageUpload
     *
     * @param Request $request
     * @return object (File)
     */
    public function imageUpload(Request $request, $fieldName, $uploadLocation, $prependName = '')
    {
        if(!$request->hasFile($fieldName)) {
            die('Request does not have file: '.$fieldName);
        }

        $newImageName = $prependName.time().'.'.$request->$fieldName->extension();
        $request->$fieldName->move(public_path('images/'.$uploadLocation), $newImageName);

        return $newImageName;
    }

    public function wysiwygUpload(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg|max:256',
        ]);
        
        $newImageName = 'cnt_'.time().'.'. $request->file('file')->extension();
        $request->file->move(public_path('images/content'), $newImageName);
        $fullPath = "/images/content/".$newImageName;

        return ['location' => $fullPath];
    }

    public function buildTableFields($tableFieldsEdit, $instanceToEdit = null)
    {
        $tableFields = [];
        foreach($tableFieldsEdit as $k => $v)
        {
            // Chevk special first
            if($k == "-html")
            {
                $tableFields[$k] = new \App\Classes\Admin\TableField_HTML($k, ['default' => $v]);
            }
            else
            {
                if($instanceToEdit != null)
                    $v['default'] = $instanceToEdit->$k;

                // Check types
                if($v['type'] == "text")
                    $tableFields[$k] = new \App\Classes\Admin\TableField_Text($k, $v);
                if($v['type'] == "password")
                    $tableFields[$k] = new \App\Classes\Admin\TableField_Password($k, $v);
                else if($v['type'] == "textarea")
                    $tableFields[$k] = new \App\Classes\Admin\TableField_TextArea($k, $v);
                else if($v['type'] == "email")
                    $tableFields[$k] = new \App\Classes\Admin\TableField_Email($k, $v);
                else if($v['type'] == "wysiwyg")
                    $tableFields[$k] = new \App\Classes\Admin\TableField_WYSIWYG($k, $v);
                else if($v['type'] == "image")
                    $tableFields[$k] = new \App\Classes\Admin\TableField_Image($k, $v);
                else if($v['type'] == "datetime")
                    $tableFields[$k] = new \App\Classes\Admin\TableField_DateTime($k, $v);
                else if($v['type'] == "select")
                    $tableFields[$k] = new \App\Classes\Admin\TableField_Select($k, $v);
                else if($v['type'] == "tags")
                    $tableFields[$k] = new \App\Classes\Admin\TableField_Tags($k, $v);
            }
        }

        return $tableFields;
    }

    private static function checkWildcard($needle, $haystack)
    {
        $commandAllowed = false;

        foreach($haystack as $commandWildcard)
        {
            if(Str::is($commandWildcard, $needle))
            {
                $commandAllowed = true;
                break;
            }
        }

        return $commandAllowed;
    }
}