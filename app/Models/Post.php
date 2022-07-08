<?php

namespace App\Models;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'slug',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('title')
        ->saveSlugsTo('slug');
    }

    public function saveBody(StorePostRequest|UpdatePostRequest $req)
    {
        $contents = collect($req->body)->map(function($i) {
            if($i['type'] == 'image')
            {
                $file_path = $i['value']->store($this->slug);
                $i['value'] = "storage/$file_path";
            }
            return [
                'type' => $i['type'],
                'value' => $i['value']
            ];
        });

        $this->body = $this->body()->createMany($contents->toArray());
        $this->adjustBodyImagesPaths();
    }

    public function adjustBodyImagesPaths()
    {
        $this->body->transform(fn($i) => [
            'id' => $i->id,
            'type' => $i['type'],
            'value' => $i['type'] == 'image'
                ? asset($i['value'])
                : $i['value']
        ]);
        return $this;
    }

    public function body()
    {
        return $this->hasMany(PostContent::class, 'id_post');
    }
}
