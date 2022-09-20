<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\MailResetPasswordNotification;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'type',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return Attribute
     */
    protected function type(): Attribute
    {
        return new Attribute(get: fn($value) => ["user", "admin", 'seller'][$value],);
    }

    /**
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function updateWithProfile(array $data) : User
    {
        try {
            if (isset($data['image'])) {
                Profile::uploadPhoto($data['image']);
            }
            DB::beginTransaction();
            $this->fill($data);
            $this->save();
            Profile::saveProfile($this, $data);
            DB::commit();
        } catch (Exception $e) {
            if (isset($data['image'])) {
                Profile::deleteFile($data['image']);
            }
            DB::rollBack();
            throw $e;
        }
        return $this;
    }

    /**
     * @param array $attributes
     * @return User
     */
    public function fill(array $attributes): User
    {
        !isset($attributes['password']) ? : $attributes['password'] = bcrypt($attributes['password']);
        return parent::fill($attributes);
    }

    /**
     * @return HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class)->withDefault();
    }
}
