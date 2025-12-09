<x-layouts.app :title="$title" :metaDescription="$metaDescription">
    <div class="legal-page">
        <div class="legal-header">
            <h1><i class="fas fa-envelope"></i> Contact Us</h1>
        </div>

        <div class="legal-content card">
            <div class="card-body">
                <section class="legal-section">
                    <h2>Get in Touch</h2>
                    <p>
                        We'd love to hear from you! Whether you have questions, feedback, suggestions, or just want to
                        say hello, there are several ways to reach the Los Santos Radio team.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>Discord Community</h2>
                    <p>
                        The fastest way to reach us and connect with the community is through our Discord server.
                        Join thousands of other listeners, chat with DJs, participate in events, and get real-time
                        support from our team and community members.
                    </p>
                    @if(config('services.discord.invite_url'))
                        <p>
                            <a href="{{ config('services.discord.invite_url') }}" class="btn btn-primary" target="_blank" rel="noopener">
                                <i class="fab fa-discord"></i> Join Our Discord Server
                            </a>
                        </p>
                    @endif
                </section>

                <section class="legal-section">
                    <h2>Social Media</h2>
                    <p>
                        Follow us on social media for updates, announcements, and community highlights:
                    </p>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
                        @if(config('services.discord.invite_url'))
                            <a href="{{ config('services.discord.invite_url') }}" class="btn btn-secondary" target="_blank" rel="noopener">
                                <i class="fab fa-discord"></i> Discord
                            </a>
                        @endif
                        {{-- Add other social media links as they become available --}}
                    </div>
                </section>

                <section class="legal-section">
                    <h2>Feedback & Suggestions</h2>
                    <p>
                        Your feedback helps us improve! We're always looking for ways to enhance your experience.
                        Share your ideas, report bugs, or suggest new features through our Discord community or
                        by reaching out to our team directly.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>DJ Applications</h2>
                    <p>
                        Interested in becoming a DJ at Los Santos Radio? We're always looking for talented and
                        passionate individuals to join our team. Reach out to us on Discord to learn more about
                        the application process and requirements.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>Partnership Opportunities</h2>
                    <p>
                        Looking to partner with Los Santos Radio for events, sponsorships, or collaborations?
                        We're open to working with brands, content creators, and organizations that align with
                        our community values. Contact us through Discord to discuss opportunities.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>Technical Support</h2>
                    <p>
                        Having technical issues with the stream or website? Our community and support team are
                        available on Discord to help troubleshoot any problems you're experiencing. Please provide
                        as much detail as possible about the issue, including:
                    </p>
                    <ul>
                        <li>What you were trying to do</li>
                        <li>What happened instead</li>
                        <li>Your browser and device information</li>
                        <li>Any error messages you received</li>
                    </ul>
                </section>

                <section class="legal-section">
                    <h2>Response Times</h2>
                    <p>
                        We strive to respond to all inquiries as quickly as possible. Discord messages and community
                        posts typically receive responses within a few hours during peak times. For more complex
                        issues or partnership inquiries, please allow 1-3 business days for a response.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>Stay Connected</h2>
                    <p>
                        Don't forget to tune in to the radio stream, participate in events, and engage with the
                        community. The more involved you are, the better your Los Santos Radio experience will be!
                    </p>
                    <p style="margin-top: 1.5rem;">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Back to Home
                        </a>
                    </p>
                </section>
            </div>
        </div>
    </div>

    
</x-layouts.app>
