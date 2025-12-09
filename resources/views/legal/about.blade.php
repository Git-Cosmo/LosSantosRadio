<x-layouts.app :title="$title" :metaDescription="$metaDescription">
    <div class="legal-page">
        <div class="legal-header">
            <h1><i class="fas fa-info-circle"></i> About Us</h1>
        </div>

        <div class="legal-content card">
            <div class="card-body">
                <section class="legal-section">
                    <h2>Welcome to Los Santos Radio</h2>
                    <p>
                        Los Santos Radio is your premier online radio and gaming community hub. We combine the best of
                        music streaming, community engagement, and gaming culture to create a unique entertainment experience.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>What We Offer</h2>
                    
                    <h3>24/7 Radio Streaming</h3>
                    <p>
                        Enjoy continuous music streaming powered by AzuraCast technology. Our station features a diverse
                        selection of music to keep you entertained around the clock.
                    </p>

                    <h3>Interactive Community</h3>
                    <p>
                        Connect with fellow listeners through our community features including events, polls, comments,
                        and live chat. Share your favorite moments and discover new content together.
                    </p>

                    <h3>Gaming Hub</h3>
                    <p>
                        Stay updated with the latest gaming news, discover game deals, find free games, and explore
                        our curated game library. We're passionate about bringing gamers together.
                    </p>

                    <h3>DJ Profiles & Schedules</h3>
                    <p>
                        Meet our talented DJs and hosts who bring personality and energy to your listening experience.
                        Check out their profiles, schedules, and connect with your favorites.
                    </p>

                    <h3>Videos & Content</h3>
                    <p>
                        Enjoy YLYL (You Laugh You Lose) videos, streamer clips, and other entertaining content from
                        across the gaming and entertainment community.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>Our Mission</h2>
                    <p>
                        At Los Santos Radio, we're committed to building a vibrant and inclusive community where music
                        lovers and gamers can come together, share experiences, and discover new content. We strive to
                        provide high-quality streaming, engaging features, and a welcoming environment for everyone.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>Technology</h2>
                    <p>
                        Our platform is powered by cutting-edge technology including AzuraCast for radio streaming,
                        Laravel for robust web services, and modern real-time features to enhance your experience.
                        We're constantly evolving and adding new features based on community feedback.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>Get Involved</h2>
                    <p>
                        Want to be part of the Los Santos Radio community? Join our Discord server, participate in
                        events, request songs, vote in polls, and help shape the future of the station. We value
                        every member of our community and welcome your input.
                    </p>
                    @if(config('services.discord.invite_url'))
                        <p>
                            <a href="{{ config('services.discord.invite_url') }}" class="btn btn-primary" target="_blank" rel="noopener">
                                <i class="fab fa-discord"></i> Join Our Discord
                            </a>
                        </p>
                    @endif
                </section>
            </div>
        </div>
    </div>

    
</x-layouts.app>
