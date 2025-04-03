<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
class Faq extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['question', 'answer'];
    protected $fillable = ['question', 'answer'];
}
