<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    use HasFactory;

    protected $table = 'homepage_contents';

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'announcement_banner',
        'total_lawyers_count',
        'total_cases_count',
        'satisfaction_rate',
    ];
}
