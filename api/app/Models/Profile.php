<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    const BASE_PATH = 'app/public';
    const DIR_USERS = 'users';
    const DIR_USER_PHOTO = self::DIR_USERS . '/photos';
    const USER_PHOTO_PATH = self::BASE_PATH . '/' . self::DIR_USER_PHOTO;

    protected $fillable = [
        'user', 'phone', 'image'
    ];

    /**
     * @param User $user
     * @param array $data
     * @return HasOne
     */
    public static function saveProfile(User $user, array $data) : HasOne
    {
        if (array_key_exists('image', $data)) {
            self::deletePhoto($user->profile());
            $data['image'] = Profile::getPhotoHashName($data['image']);
        }

        $user->profile()->fill($data)->save();

        return $user->profile();
    }

    /**
     * @param Profile $profile
     * @return void
     */
    public static function deletePhoto(Profile $profile)
    {
        if (!$profile->image) {
            return;
        }

        $dir = self::photoDir();

        Storage::disk('public')->delete("{$dir}/{$profile->image}");
    }

    /**
     * @param UploadedFile|null $image
     * @return null
     */
    private static function getPhotoHashName(UploadedFile $image = null)
    {
        return $image?->hashName();
    }

    /**
     * @return string
     */
    public static function photoDir(): string
    {
        return self::DIR_USERS;
    }

    /**
     * @param UploadedFile|null $image
     * @return void
     */
    public static function deleteFile(UploadedFile $image = null): void
    {
        if (!$image) {
            return;
        }

        $path = self::photosPath();

        $filePath = "{$path}/{$image->hashName()}";

        if (file_exists($filePath)) {
            File::delete($filePath);
        }
    }

    /**
     * @return string
     */
    public static function photosPath(): string
    {
        $path = self::USER_PHOTO_PATH;
        return storage_path($path);
    }

    /**
     * @param $image
     * @return void
     */
    public static function uploadPhoto($image): void
    {
        if (!$image) {
            return;
        }

        $dir = self::photoDir();

        $image->store($dir, ['disk' => 'public']);
    }

    /**
     * @return string
     */
    public function getPhotoUrlBaseAttribute(): string
    {
        $path = self::photoDir();
        return $this->image ? "{$path}/{$this->image}" : "https://secure.gravatar.com/avatar/8d0153955da67e7593b0cca28e3e4d75.jpg?s=150&r=g&d=mm";
    }

    /**
     * @return mixed|string
     */
    public function getPhotoUriAttribute(): mixed
    {
        return $this->image ? asset("storage/{$this->image_url_base}") : $this->image_url_base;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
