<?php

namespace App\Models;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
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

    private function storeImgAndGetPathIfIsImg(Array $item): string
    {
        return $item['type'] == 'image'
            ? $item['value']->store($this->slug)
            : $item['value'];
    }

    public static function homeCacheRemember(): LengthAwarePaginator
    {
        $page = request('page', 1);
        return cache()->remember("home-$page", 60*60*24, fn() =>
            Post::with('body')
                ->orderBy('created_at', 'desc')
                ->paginate()
        );
    }

    public static function homeCacheForget(): void
    {
        $total_pages = (int) ceil(Post::count() / 15);
        for($page = 1; $page <= $total_pages; $page++)
        {
            cache()->forget("home-$page");
        }
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('title')
        ->saveSlugsTo('slug');
    }

    public function updateBody(UpdatePostRequest $req): Post
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

    public function saveBody(StorePostRequest $req): void
    {
        $contents = collect($req->body)->map(fn($i, $k) => [
            'order' => $k,
            'type' => $i['type'],
            'value' => $this->storeImgAndGetPathIfIsImg($i),
        ]);

        $this->body = $this->body()->createMany($contents->toArray());
        $this->adjustBodyImagesPaths();
    }

    public function adjustBodyImagesPaths(): Post
    {
        $this->body->transform(fn($i, $k) => [
            'order' => $k,
            'id' => $i->id,
            'type' => $i['type'],
            'value' => $i['type'] == 'image'
                ? route('post.image', $i['value'])
                : $i['value']
        ]);
        return $this;
    }

    public function deleteImages(): Post
    {
        Storage::deleteDirectory($this->slug);
        return $this;
    }

    public function body()
    {
        return $this->hasMany(PostContent::class, 'id_post')->orderBy('order', 'asc');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_post')->orderBy('created_at', 'desc');
    }
}
