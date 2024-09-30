<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \App\Http\BlindIndexingHelpers;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'name_index',  // Blind index for name
        'email',
        'email_index', // Blind index for email
        'phone',
        'address',
        'ssn',
        'ssn_index',   // Blind index for SSN
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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


    // Decrypts the fields after fetching from the database
    public function getNameAttribute($value)
    {
        return BlindIndexingHelpers::decryptFieldValue("users", "name", $value);
    }

    public function getEmailAttribute($value)
    {
        return BlindIndexingHelpers::decryptFieldValue("users", "email", $value);
    }

    public function getSsnAttribute($value)
    {
        return BlindIndexingHelpers::decryptFieldValue("users", "ssn", $value);
    }
}
