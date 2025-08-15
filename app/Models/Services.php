<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Services extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'category',
        'description',
        'image',
        'gallery',
    ];

    protected $casts = [
        'gallery' => 'array', // This will handle JSON conversion automatically
    ];

    protected $hidden = [
        // Add any fields you want to hide from JSON output
    ];

    // Accessor for gallery - handle both string and array cases
    public function getGalleryAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }
        return $value ?: [];
    }

    // Mutator for gallery - ensure it's stored as JSON
    public function setGalleryAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['gallery'] = json_encode($value);
        } elseif (is_string($value)) {
            // If it's already a JSON string, store as is
            $this->attributes['gallery'] = $value;
        } else {
            $this->attributes['gallery'] = json_encode([]);
        }
    }

    // Scopes
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeActive($query)
    {
        // Add any active/inactive logic if needed in the future
        return $query;
    }
}
