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

    public function convertHtmlToMarkdown($text)
    {
        // Convert <a> tags to Markdown links
        $text = preg_replace_callback('/<a href="([^"]+)"[^>]*>(.*?)<\/a>/i', function ($matches) {
            return '[' . $matches[2] . '](' . $matches[1] . ')';
        }, $text);
    
        // Convert <strong> to Markdown bold
        $text = preg_replace('/<strong>(.*?)<\/strong>/i', '**$1**', $text);
    
        // Convert <i> to Markdown italic
        $text = preg_replace('/<i>(.*?)<\/i>/i', '*$1*', $text);
    
        // Convert <h1> to <h6> into Markdown headings with a limit of 3 #
        for ($i = 1; $i <= 6; $i++) {
            $text = preg_replace('/<h' . $i . '[^>]*>(.*?)<\/h' . $i . '>/i', str_repeat('#', min($i, 3)) . ' $1', $text);
        }
    
        // Convert <ul> and <li> to Markdown lists
        $text = preg_replace_callback('/<ul[^>]*>(.*?)<\/ul>/is', function ($matches) {
            return preg_replace('/<li[^>]*>(.*?)<\/li>/is', '- $1', $matches[1]);
        }, $text);
    
        // Convert <ol> and <li> to numbered Markdown lists
        $text = preg_replace_callback('/<ol[^>]*>(.*?)<\/ol>/is', function ($matches) {
            $markdownList = '';
            $items = preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $matches[1], $liMatches);
            foreach ($liMatches[1] as $index => $item) {
                $markdownList .= ($index + 1) . '. ' . strip_tags($item) . PHP_EOL;
            }
            return $markdownList;
        }, $text);
    
        // Convert <blockquote> to Markdown blockquotes
        $text = preg_replace('/<blockquote[^>]*>(.*?)<\/blockquote>/is', '> $1', $text);
    
        // Convert <code> to Markdown inline code
        $text = preg_replace('/<code>(.*?)<\/code>/is', '`$1`', $text);
    
        // Convert <hr> to Markdown horizontal rule
        $text = preg_replace('/<hr[^>]*>/i', '---', $text);
    
        // Remove all remaining HTML tags
        $text = strip_tags($text);
    
        return $text;
    }    

    public function createDiscordWebhook(User $author): DiscordWebhook
    {
        
        $embed = Embed::create()
            ->title($this->name)
            ->author($author->name, null, $author->getAvatar())
            ->description(Str::limit($this->convertHtmlToMarkdown($this->description), 1995))
            ->url(route('changelog.categories.show', $this->category))
            ->footer($this->category->name)
            ->timestamp(now());

        return DiscordWebhook::create()->addEmbed($embed);
    }
}
