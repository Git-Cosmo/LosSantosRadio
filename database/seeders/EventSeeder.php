<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds major gaming events happening in 2026.
     */
    public function run(): void
    {
        $events = [
            // Major Gaming Conventions & Expos
            [
                'title' => 'E3 2026',
                'description' => 'The Electronic Entertainment Expo returns with the latest game announcements, demos, and industry insights. Experience the future of gaming with major publishers showcasing their upcoming titles.',
                'event_type' => 'expo',
                'starts_at' => '2026-06-09 09:00:00',
                'ends_at' => '2026-06-11 18:00:00',
                'location' => 'Los Angeles Convention Center, CA',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Gamescom 2026',
                'description' => 'Europe\'s largest gaming event brings together gamers, developers, and publishers. Featuring hands-on demos, cosplay competitions, and exclusive announcements from top gaming companies.',
                'event_type' => 'expo',
                'starts_at' => '2026-08-25 10:00:00',
                'ends_at' => '2026-08-29 20:00:00',
                'location' => 'Koelnmesse, Cologne, Germany',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'PAX East 2026',
                'description' => 'Penny Arcade Expo East celebrates gaming culture with indie showcases, tabletop gaming, panels, and community meetups. A must-attend for passionate gamers and content creators.',
                'event_type' => 'expo',
                'starts_at' => '2026-03-26 10:00:00',
                'ends_at' => '2026-03-29 18:00:00',
                'location' => 'Boston Convention Center, MA',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Tokyo Game Show 2026',
                'description' => 'Japan\'s premier gaming event showcasing the latest from Japanese developers and international publishers. Experience cutting-edge technology, VR demos, and exclusive game reveals.',
                'event_type' => 'expo',
                'starts_at' => '2026-09-24 10:00:00',
                'ends_at' => '2026-09-27 17:00:00',
                'location' => 'Makuhari Messe, Tokyo, Japan',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'PAX West 2026',
                'description' => 'The original PAX returns to Seattle with a celebration of all things gaming. Featuring tournament play, indie developers, panels with industry legends, and more.',
                'event_type' => 'expo',
                'starts_at' => '2026-09-04 10:00:00',
                'ends_at' => '2026-09-07 18:00:00',
                'location' => 'Washington State Convention Center, Seattle, WA',
                'is_featured' => false,
                'is_published' => true,
            ],

            // Esports Events
            [
                'title' => 'The International 2026',
                'description' => 'Dota 2\'s premier championship tournament featuring the best teams competing for millions in prizes. Watch the world\'s top players battle for esports supremacy.',
                'event_type' => 'tournament',
                'starts_at' => '2026-08-15 12:00:00',
                'ends_at' => '2026-08-23 21:00:00',
                'location' => 'Climate Pledge Arena, Seattle, WA',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'League of Legends World Championship 2026',
                'description' => 'The culmination of the LoL competitive season. Regional champions compete for the Summoner\'s Cup and the title of world champion in the most-watched esports event.',
                'event_type' => 'tournament',
                'starts_at' => '2026-10-01 14:00:00',
                'ends_at' => '2026-11-07 20:00:00',
                'location' => 'Multiple Cities Worldwide',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'EVO 2026',
                'description' => 'Evolution Championship Series - the world\'s largest fighting game tournament. Featuring Street Fighter, Tekken, Mortal Kombat, and more with the best FGC players.',
                'event_type' => 'tournament',
                'starts_at' => '2026-08-07 09:00:00',
                'ends_at' => '2026-08-09 23:00:00',
                'location' => 'Las Vegas Convention Center, NV',
                'is_featured' => false,
                'is_published' => true,
            ],
            [
                'title' => 'VALORANT Champions 2026',
                'description' => 'The pinnacle of VALORANT esports competition. Top teams from around the world compete for the championship title and massive prize pool.',
                'event_type' => 'tournament',
                'starts_at' => '2026-09-10 13:00:00',
                'ends_at' => '2026-09-20 22:00:00',
                'location' => 'TBD',
                'is_featured' => false,
                'is_published' => true,
            ],

            // Game Releases / Launch Events
            [
                'title' => 'Grand Theft Auto VI Launch',
                'description' => 'The most anticipated game of the decade finally launches! Join millions of players worldwide as Vice City comes to life with next-gen graphics and revolutionary gameplay.',
                'event_type' => 'release',
                'starts_at' => '2026-03-15 00:00:00',
                'ends_at' => '2026-03-15 23:59:59',
                'location' => 'Worldwide Digital Release',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Nintendo Direct - Spring 2026',
                'description' => 'Nintendo\'s digital showcase revealing upcoming games for Nintendo Switch. Expect major announcements, gameplay trailers, and release dates for highly anticipated titles.',
                'event_type' => 'announcement',
                'starts_at' => '2026-02-12 14:00:00',
                'ends_at' => '2026-02-12 15:00:00',
                'location' => 'Online Stream',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'PlayStation State of Play - Summer 2026',
                'description' => 'Sony\'s showcase event featuring announcements and deep dives into upcoming PlayStation 5 games. Tune in for world premieres and exclusive gameplay reveals.',
                'event_type' => 'announcement',
                'starts_at' => '2026-05-28 14:00:00',
                'ends_at' => '2026-05-28 15:30:00',
                'location' => 'Online Stream',
                'is_featured' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Xbox Games Showcase 2026',
                'description' => 'Microsoft\'s annual showcase of Xbox and PC games. Featuring Game Pass announcements, first-party exclusives, and third-party partnerships.',
                'event_type' => 'announcement',
                'starts_at' => '2026-06-11 10:00:00',
                'ends_at' => '2026-06-11 12:00:00',
                'location' => 'Online Stream',
                'is_featured' => false,
                'is_published' => true,
            ],

            // Community Events
            [
                'title' => 'Summer Game Fest 2026',
                'description' => 'Geoff Keighley\'s multi-day celebration of gaming with world premieres, gameplay reveals, and developer interviews. The kickoff to the summer gaming season.',
                'event_type' => 'expo',
                'starts_at' => '2026-06-05 11:00:00',
                'ends_at' => '2026-06-08 20:00:00',
                'location' => 'Los Angeles, CA / Online',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'The Game Awards 2026',
                'description' => 'Gaming\'s biggest night celebrating the year\'s best games, developers, and content creators. Featuring musical performances, celebrity appearances, and surprise announcements.',
                'event_type' => 'awards',
                'starts_at' => '2026-12-10 17:30:00',
                'ends_at' => '2026-12-10 21:00:00',
                'location' => 'Microsoft Theater, Los Angeles, CA',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'QuakeCon 2026',
                'description' => 'id Software\'s legendary free convention for fans of DOOM, Quake, and other Bethesda titles. Featuring LAN parties, tournaments, and developer panels.',
                'event_type' => 'convention',
                'starts_at' => '2026-08-13 09:00:00',
                'ends_at' => '2026-08-16 18:00:00',
                'location' => 'Gaylord Texan, Dallas, TX',
                'is_featured' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Awesome Games Done Quick 2026',
                'description' => 'Week-long speedrunning marathon charity event benefiting the Prevent Cancer Foundation. Watch world-record attempts and incredible gaming feats 24/7.',
                'event_type' => 'charity',
                'starts_at' => '2026-01-04 11:30:00',
                'ends_at' => '2026-01-11 02:00:00',
                'location' => 'Online Stream',
                'is_featured' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Summer Games Done Quick 2026',
                'description' => 'Speedrunning charity marathon benefiting Doctors Without Borders. Week-long celebration of gaming skill and community generosity.',
                'event_type' => 'charity',
                'starts_at' => '2026-06-28 11:30:00',
                'ends_at' => '2026-07-05 02:00:00',
                'location' => 'Minneapolis, MN / Online Stream',
                'is_featured' => false,
                'is_published' => true,
            ],
        ];

        foreach ($events as $event) {
            // Generate slug if not present
            if (! isset($event['slug'])) {
                $event['slug'] = Str::slug($event['title']);
            }

            Event::firstOrCreate(
                ['slug' => $event['slug']],
                $event
            );
        }

        if ($this->command) {
            $this->command->info('Gaming events seeded successfully! Added '.count($events).' events for 2026.');
        }
    }
}
