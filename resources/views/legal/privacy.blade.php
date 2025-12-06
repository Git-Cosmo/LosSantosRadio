<x-layouts.app :title="$title" :metaDescription="$metaDescription">
    <div class="legal-page">
        <div class="legal-header">
            <h1><i class="fas fa-shield-alt"></i> Privacy Policy</h1>
            <p class="legal-updated">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="legal-content card">
            <div class="card-body">
                <section class="legal-section">
                    <h2>1. Introduction</h2>
                    <p>
                        Los Santos Radio ("we", "us", or "our") respects your privacy and is committed to protecting your
                        personal data. This Privacy Policy explains how we collect, use, disclose, and safeguard your
                        information when you use our online radio streaming service.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>2. Information We Collect</h2>
                    
                    <h3>2.1 Information You Provide</h3>
                    <p>When you create an account through third-party authentication, we may collect:</p>
                    <ul>
                        <li>Username and display name</li>
                        <li>Email address (if provided by the authentication provider)</li>
                        <li>Profile picture/avatar</li>
                        <li>Unique identifier from the authentication provider</li>
                    </ul>

                    <h3>2.2 Information Collected Automatically</h3>
                    <p>When you use our Service, we automatically collect:</p>
                    <ul>
                        <li>IP address and approximate location</li>
                        <li>Browser type and version</li>
                        <li>Device information</li>
                        <li>Pages visited and time spent on pages</li>
                        <li>Listening activity and song requests</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>
                </section>

                <section class="legal-section">
                    <h2>3. How We Use Your Information</h2>
                    <p>We use the collected information to:</p>
                    <ul>
                        <li>Provide and maintain our Service</li>
                        <li>Process song requests and personalize your experience</li>
                        <li>Track listening statistics and generate leaderboards</li>
                        <li>Communicate with you about updates and features</li>
                        <li>Analyze usage patterns to improve our Service</li>
                        <li>Detect and prevent fraud and abuse</li>
                        <li>Comply with legal obligations</li>
                    </ul>
                </section>

                <section class="legal-section">
                    <h2>4. Information Sharing</h2>
                    <p>We may share your information in the following circumstances:</p>
                    <ul>
                        <li><strong>Public Information:</strong> Your username, avatar, and listening activity may be visible to other users on leaderboards and in the community</li>
                        <li><strong>Service Providers:</strong> We may share data with third-party service providers who assist in operating our Service</li>
                        <li><strong>Legal Requirements:</strong> We may disclose information when required by law or to protect our rights</li>
                        <li><strong>Business Transfers:</strong> In the event of a merger or acquisition, your information may be transferred</li>
                    </ul>
                    <p>We do not sell your personal information to third parties.</p>
                </section>

                <section class="legal-section">
                    <h2>5. Third-Party Authentication</h2>
                    <p>
                        We use OAuth authentication providers (Discord, Twitch, Steam, Battle.net) to enable account creation.
                        When you authenticate with these services:
                    </p>
                    <ul>
                        <li>You authorize us to access certain information from your account</li>
                        <li>We only request the minimum permissions necessary</li>
                        <li>The authentication providers have their own privacy policies</li>
                        <li>You can revoke access at any time through the provider's settings</li>
                    </ul>
                </section>

                <section class="legal-section">
                    <h2>6. Cookies and Tracking</h2>
                    <p>
                        We use cookies and similar technologies to enhance your experience. For detailed information,
                        please see our <a href="{{ route('legal.cookies') }}">Cookie Policy</a>.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>7. Data Security</h2>
                    <p>
                        We implement appropriate technical and organizational measures to protect your personal information,
                        including:
                    </p>
                    <ul>
                        <li>Encryption of data in transit using HTTPS</li>
                        <li>Secure storage of authentication tokens</li>
                        <li>Regular security assessments</li>
                        <li>Access controls limiting who can view personal data</li>
                    </ul>
                    <p>
                        However, no method of transmission over the Internet is 100% secure, and we cannot guarantee
                        absolute security.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>8. Data Retention</h2>
                    <p>
                        We retain your personal information for as long as your account is active or as needed to provide
                        our Service. You may request deletion of your account and associated data at any time by contacting us.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>9. Your Rights</h2>
                    <p>Depending on your location, you may have the following rights regarding your personal data:</p>
                    <ul>
                        <li><strong>Access:</strong> Request a copy of your personal data</li>
                        <li><strong>Correction:</strong> Request correction of inaccurate data</li>
                        <li><strong>Deletion:</strong> Request deletion of your data</li>
                        <li><strong>Portability:</strong> Request transfer of your data</li>
                        <li><strong>Objection:</strong> Object to certain processing of your data</li>
                        <li><strong>Withdrawal:</strong> Withdraw consent where processing is based on consent</li>
                    </ul>
                    <p>To exercise these rights, please contact us through our Discord server.</p>
                </section>

                <section class="legal-section">
                    <h2>10. Children's Privacy</h2>
                    <p>
                        Our Service is not intended for children under 13 years of age. We do not knowingly collect
                        personal information from children under 13. If we become aware that we have collected data from
                        a child under 13, we will take steps to delete such information.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>11. International Users</h2>
                    <p>
                        Our Service is operated from various locations. If you are accessing the Service from outside
                        the operating country, please be aware that your information may be transferred to, stored,
                        and processed in different jurisdictions.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>12. Changes to This Policy</h2>
                    <p>
                        We may update this Privacy Policy from time to time. We will notify you of any changes by posting
                        the new Privacy Policy on this page and updating the "Last updated" date. Your continued use of
                        the Service after changes are posted constitutes your acceptance of the updated policy.
                    </p>
                </section>

                <section class="legal-section">
                    <h2>13. Contact Us</h2>
                    <p>
                        If you have any questions about this Privacy Policy or our data practices, please contact us
                        through our Discord server or via the contact information provided on our website.
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
    </style>
</x-layouts.app>
