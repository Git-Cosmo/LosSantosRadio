<x-layouts.app :title="$title" :metaDescription="$metaDescription">
    <div class="legal-page">
        <div class="legal-header">
            <h1><i class="fas fa-cookie-bite"></i> Cookie Policy</h1>
            <p class="legal-updated">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="legal-content card">
            <div class="card-body">
                <section class="legal-section">
                    <h2>1. What Are Cookies?</h2>
                    <p>
                        Cookies are small text files that are stored on your device (computer, tablet, or mobile) when you
                        visit a website. They help websites remember your preferences and provide a better user experience.
                        Cookies can be "session cookies" (deleted when you close your browser) or "persistent cookies"
                        (remain on your device for a set period).
                    </p>
                </section>

                <section class="legal-section">
                    <h2>2. How We Use Cookies</h2>
                    <p>Los Santos Radio uses cookies for the following purposes:</p>

                    <h3>2.1 Essential Cookies</h3>
                    <p>
                        These cookies are necessary for the website to function properly. They enable core functionality
                        such as security, account authentication, and session management. These cookies cannot be disabled.
                    </p>
                    <div class="cookie-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Cookie Name</th>
                                    <th>Purpose</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>laravel_session</code></td>
                                    <td>Maintains your session across pages</td>
                                    <td>Session</td>
                                </tr>
                                <tr>
                                    <td><code>XSRF-TOKEN</code></td>
                                    <td>Prevents cross-site request forgery attacks</td>
                                    <td>Session</td>
                                </tr>
                                <tr>
                                    <td><code>cookie_consent</code></td>
                                    <td>Stores your cookie preferences</td>
                                    <td>1 year</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3>2.2 Functional Cookies</h3>
                    <p>
                        These cookies enable enhanced functionality and personalization, such as remembering your preferences.
                    </p>
                    <div class="cookie-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Cookie Name</th>
                                    <th>Purpose</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>theme</code></td>
                                    <td>Remembers your dark/light mode preference</td>
                                    <td>Persistent</td>
                                </tr>
                                <tr>
                                    <td><code>clockFormat</code></td>
                                    <td>Remembers your 12/24 hour clock preference</td>
                                    <td>Persistent</td>
                                </tr>
                                <tr>
                                    <td><code>volume</code></td>
                                    <td>Remembers your audio player volume setting</td>
                                    <td>Persistent</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3>2.3 Analytics Cookies</h3>
                    <p>
                        These cookies help us understand how visitors interact with our website by collecting and
                        reporting information anonymously. This helps us improve our Service.
                    </p>
                    <p>
                        <strong>Note:</strong> Analytics cookies are only enabled if you accept "All Cookies" in our
                        consent banner.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>3. Local Storage</h2>
                    <p>
                        In addition to cookies, we use browser local storage to save certain preferences. Local storage
                        works similarly to cookies but can store larger amounts of data. We use local storage for:
                    </p>
                    <ul>
                        <li>Theme preferences (dark/light mode)</li>
                        <li>Clock format preferences (12/24 hour)</li>
                        <li>Cookie consent preferences</li>
                        <li>Player settings and volume</li>
                    </ul>
                </section>

                <section class="legal-section">
                    <h2>4. Third-Party Cookies</h2>
                    <p>
                        We may use third-party services that set their own cookies. These may include:
                    </p>
                    <ul>
                        <li><strong>Authentication Providers:</strong> Discord, Twitch, Steam, and Battle.net may set
                            cookies when you log in through their services</li>
                        <li><strong>Content Delivery Networks (CDNs):</strong> We use CDNs to deliver fonts and
                            scripts, which may set performance cookies</li>
                    </ul>
                    <p>
                        These third-party services have their own privacy and cookie policies. We encourage you to
                        review their policies.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>5. Managing Your Cookie Preferences</h2>
                    <p>You can control cookies in several ways:</p>
                    
                    <h3>5.1 Our Cookie Consent Banner</h3>
                    <p>
                        When you first visit our website, you will see a cookie consent banner. You can choose to:
                    </p>
                    <ul>
                        <li><strong>Accept All:</strong> Enable all cookies including analytics</li>
                        <li><strong>Essential Only:</strong> Enable only cookies necessary for the website to function</li>
                    </ul>
                    <p>
                        To change your preferences later, you can clear your browser's local storage or cookies and
                        refresh the page to see the consent banner again.
                    </p>

                    <h3>5.2 Browser Settings</h3>
                    <p>
                        Most browsers allow you to control cookies through their settings. You can usually find these
                        options in your browser's "Privacy" or "Security" settings. Here are links to cookie settings
                        for common browsers:
                    </p>
                    <ul>
                        <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer" target="_blank" rel="noopener">Mozilla Firefox</a></li>
                        <li><a href="https://support.apple.com/guide/safari/manage-cookies-sfri11471/mac" target="_blank" rel="noopener">Safari</a></li>
                        <li><a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener">Microsoft Edge</a></li>
                    </ul>
                    <p>
                        <strong>Note:</strong> Blocking essential cookies may prevent you from using certain features
                        of our website.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>6. Changes to This Policy</h2>
                    <p>
                        We may update this Cookie Policy from time to time to reflect changes in our practices or for
                        other operational, legal, or regulatory reasons. We will notify you of any material changes by
                        posting the new Cookie Policy on this page.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>7. Contact Us</h2>
                    <p>
                        If you have any questions about our use of cookies, please contact us through our Discord
                        server or via the contact information provided on our website.
                    </p>
                </section>
            </div>
        </div>
    </div>

    <style>
        .legal-page {
            max-width: 800px;
            margin: 0 auto;
        }

        .legal-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .legal-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--color-text-primary);
        }

        .legal-header h1 i {
            color: var(--color-accent);
            margin-right: 0.5rem;
        }

        .legal-updated {
            color: var(--color-text-muted);
            font-size: 0.875rem;
        }

        .legal-content {
            line-height: 1.7;
        }

        .legal-section {
            margin-bottom: 2rem;
        }

        .legal-section:last-child {
            margin-bottom: 0;
        }

        .legal-section h2 {
            font-size: 1.25rem;
            color: var(--color-text-primary);
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--color-border);
        }

        .legal-section h3 {
            font-size: 1rem;
            color: var(--color-text-primary);
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }

        .legal-section p {
            color: var(--color-text-secondary);
            margin-bottom: 1rem;
        }

        .legal-section ul {
            color: var(--color-text-secondary);
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .legal-section ul li {
            margin-bottom: 0.5rem;
        }

        .legal-section strong {
            color: var(--color-text-primary);
        }

        .legal-section a {
            color: var(--color-accent);
        }

        .cookie-table {
            margin: 1rem 0;
            overflow-x: auto;
        }

        .cookie-table table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .cookie-table th,
        .cookie-table td {
            padding: 0.75rem;
            text-align: left;
            border: 1px solid var(--color-border);
        }

        .cookie-table th {
            background-color: var(--color-bg-tertiary);
            color: var(--color-text-primary);
            font-weight: 600;
        }

        .cookie-table td {
            color: var(--color-text-secondary);
        }

        .cookie-table code {
            background-color: var(--color-bg-tertiary);
            padding: 0.125rem 0.375rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.8125rem;
        }
    </style>
</x-layouts.app>
