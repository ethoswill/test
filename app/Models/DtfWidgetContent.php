<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DtfWidgetContent extends Model
{
    protected $table = 'dtf_widget_content';
    
    protected $fillable = [
        'widget_name',
        'content',
    ];
}
