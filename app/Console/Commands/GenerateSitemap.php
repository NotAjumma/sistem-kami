<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Organizer;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the XML sitemap for public pages';

    public function handle(): void
    {
        $sitemap = Sitemap::create();

        // Static pages
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create('/search')->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // Public organizer profiles
        Organizer::where('is_active', true)
            ->where('visibility', 'public')
            ->whereNotNull('slug')
            ->get(['id', 'slug', 'updated_at'])
            ->each(function (Organizer $organizer) use ($sitemap) {
                $sitemap->add(
                    Url::create('/' . $organizer->slug)
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($organizer->updated_at)
                );
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated at public/sitemap.xml');
    }
}
