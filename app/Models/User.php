<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $userFieldsEdit = [
        'name'                  => ['type' => 'text', 'attributes' => ['autocomplete' => 'off'], 'validation' => 'required|between:6,30', 'help' => 'Username should be between 6 and 30 characters'],
        'email'                 => ['type' => 'email', 'attributes' => ['autocomplete' => 'off'], 'validation' => 'required'],
        'image'                 => ['type' => 'image', 'path' => 'users', 'previewType' => 'square', 'validation' => 'image|mimes:jpeg,png,jpg,png,gif|max:1024', 'help' => 'Image should be a valid JPG, PNG or GIF, no larger than 1024kb. The image will be croped to square'],
        //'-html'               =>  '<hr /><h4>Update password</h4>',
        'password'              => ['type' => 'password', 'attributes' => ['autocomplete' => 'off'], 'validation' => 'between:6,64|confirmed', 'help' => 'Leave blank to keep it the same. Password should be at least 6 characters'],
        'password_confirmation' => ['type' => 'password', 'confirmation' => true, 'attributes' => ['autocomplete' => 'off'], 'help' => 'Please retype password here to confirm'],
    ];

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function news()
    {
        return $this->hasMany('App\News', 'user_id');
    }

    public function getProfileImage($includePublicPath = false)
    {
        $publicPath = '/images/users/'.$this->image;
        $fullPath = str_replace('\\', '/', str_replace('//', '/', public_path($publicPath)));

        if(!is_file($fullPath))
            return 'https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg';
        else
            return ($includePublicPath) ? $publicPath : $this->image;
    }
}
