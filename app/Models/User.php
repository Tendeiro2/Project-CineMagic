<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'name',
        'email',
        'password',
        'type',
        'blocked',
        'photo_filename',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customer():HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'id')->withTrashed();
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'customer_id');
    }


    public function getPhotoFullUrlAttribute()
    {
        if ($this->photo_filename && Storage::exists("public/photos/{$this->photo_filename}")) {
            return asset("storage/photos/{$this->photo_filename}");
        } else {
            return asset("img/default_user.png");
        }
    }
}
