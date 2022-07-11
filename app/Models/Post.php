<?php

namespace App\Models;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'slug',
    ];

    private function storeImgAndGetPathIfIsImg(Array $item)
    {
        return $item['type'] == 'image'
            ? $item['value']->store($this->slug)
            : $item['value'];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('title')
        ->saveSlugsTo('slug');
    }

    public function updateBody(UpdatePostRequest $req)
    {
        $this->body()->whereNotIn(
                'id', Arr::pluck($req->body, 'id')
            )
            ->get()
            ->map(function($i) {
                if($i->type == 'image') Storage::delete($i->value);
                $i->delete();
            });

        $new_contents = collect($req->body)->map(function($i, $k) {
            //new contents
            if(!isset($i['id'])) return [
                'order' => $k,
                'type' => $i['type'],
                'value' => $this->storeImgAndGetPathIfIsImg($i),
            ];

            //update old contents
            $post_content = PostContent::findOrFail($i['id']);
            if($i['type'] == 'image' && !empty($i['value']))
            {
                $i['value'] = $i['value']->store($this->slug);
            }
            if(empty($i['value'])) unset($i['value']);

            $i['order'] = $k;
            $post_content->update($i);
        })
        ->whereNotNull();
        
        $this->body()->createMany($new_contents->toArray());
        return $this;
    }

    public function saveBody(StorePostRequest $req)
    {
        $contents = collect($req->body)->map(fn($i, $k) => [
            'order' => $k,
            'type' => $i['type'],
            'value' => $this->storeImgAndGetPathIfIsImg($i),
        ]);

        $this->body = $this->body()->createMany($contents->toArray());
        $this->adjustBodyImagesPaths();
    }

    public function adjustBodyImagesPaths()
    {
        $this->body->transform(fn($i, $k) => [
            'order' => $k,
            'id' => $i->id,
            'type' => $i['type'],
            'value' => $i['type'] == 'image'
                ? route('post.image', $i->id)
                : $i['value']
        ]);
        return $this;
    }

    public function deleteImages()
    {
        Storage::deleteDirectory($this->slug);
        return $this;
    }

    public function body()
    {
        return $this->hasMany(PostContent::class, 'id_post')->orderBy('order', 'asc');
    }
}
