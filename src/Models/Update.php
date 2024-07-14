<?php

namespace Azuriom\Plugin\Changelog\Models;

use Azuriom\Models\Traits\HasTablePrefix;
use Azuriom\Models\User;
use Azuriom\Support\Discord\DiscordWebhook;
use Azuriom\Support\Discord\Embed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Azuriom\Plugin\Changelog\Models\Category $category
 *
 * @method static \Illuminate\Database\Eloquent\Builder enabled()
 */
class Update extends Model
{
    use HasTablePrefix;

    /**
     * The table prefix associated with the model.
     *
     * @var string
     */
    protected $prefix = 'changelog_';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'name', 'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function createDiscordWebhook(User $author): DiscordWebhook
    {
        $embed = Embed::create()
            ->title($this->name)
            ->author($author->name, null, $author->getAvatar())
            ->description(Str::limit(strip_tags($this->description), 1995))
            ->url(route('changelog.categories.show', $this->category))
            ->footer($this->category->name)
            ->timestamp(now());

        return DiscordWebhook::create()->addEmbed($embed);
    }
}
