<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\News;
use App\Models\Poll;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate
                            {--limit=1000 : Maximum items per content type}
                            {--poll-limit=500 : Maximum polls to include}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap for SEO';

    /**
     * Maximum items per content type (configurable via --limit option).
     */
    protected int $maxItemsPerType;

    /**
     * Maximum polls to include (configurable via --poll-limit option).
     */
    protected int $maxPolls;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->maxItemsPerType = (int) $this->option('limit');
        $this->maxPolls = (int) $this->option('poll-limit');

        $this->info('Generating sitemap...');
        $this->info("Max items per type: {$this->maxItemsPerType}, Max polls: {$this->maxPolls}");

        $sitemap = Sitemap::create();

        // Add static pages
        $this->addStaticPages($sitemap);

        // Add news articles
        $this->addNews($sitemap);

        // Add events
        $this->addEvents($sitemap);

        // Add polls
        $this->addPolls($sitemap);

        // Write sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at '.public_path('sitemap.xml'));

        return Command::SUCCESS;
    }

    /**
     * Add static pages to the sitemap.
     */
    protected function addStaticPages(Sitemap $sitemap): void
    {
        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)
                ->setPriority(1.0)
        );

        $sitemap->add(
            Url::create(route('schedule'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8)
        );

        $sitemap->add(
            Url::create(route('news.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8)
        );

        $sitemap->add(
            Url::create(route('events.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.7)
        );

        $sitemap->add(
            Url::create(route('polls.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.7)
        );

        $sitemap->add(
            Url::create(route('requests.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8)
        );

        $sitemap->add(
            Url::create(route('songs'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.7)
        );

        $sitemap->add(
            Url::create(route('leaderboard'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)
                ->setPriority(0.6)
        );

        $sitemap->add(
            Url::create(route('games.free'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.7)
        );

        $sitemap->add(
            Url::create(route('games.deals'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.7)
        );

        $sitemap->add(
            Url::create(route('videos.ylyl'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.6)
        );

        $sitemap->add(
            Url::create(route('videos.clips'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.6)
        );
    }

    /**
     * Add published news articles to the sitemap.
     */
    protected function addNews(Sitemap $sitemap): void
    {
        News::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take($this->maxItemsPerType)
            ->cursor()
            ->each(function ($news) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('news.show', $news->slug))
                        ->setLastModificationDate($news->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.6)
                );
            });
    }

    /**
     * Add published events to the sitemap.
     */
    protected function addEvents(Sitemap $sitemap): void
    {
        Event::where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->take($this->maxItemsPerType)
            ->cursor()
            ->each(function ($event) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('events.show', $event->slug))
                        ->setLastModificationDate($event->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.6)
                );
            });
    }

    /**
     * Add active polls to the sitemap.
     */
    protected function addPolls(Sitemap $sitemap): void
    {
        Poll::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->take($this->maxPolls)
            ->cursor()
            ->each(function ($poll) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('polls.show', $poll->slug))
                        ->setLastModificationDate($poll->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.5)
                );
            });
    }
}
