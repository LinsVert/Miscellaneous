<?php
namespace Linsvert\Spider\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SpiderModel extends Model
{
    //laravel-admin-spider task model
    protected $table = 'spider_spider';
    
    protected $casts = [
        'domains' => 'json',
        'scan_urls' => 'json',
        'list_url_regexes' => 'json',
        'content_url_regexes' => 'json',
        'export_config' => 'json',
        'db_config' => 'json',
        'queue_config' => 'json',
        'fields' => 'json'
    ];
    
}
