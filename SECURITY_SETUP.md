# Development credentials and tests

This internship repository remains private. Do not publish its code without the
appropriate permission.

The AI workflow integration test uses a deliberately fake key and mocked HTTP;
unmatched Laravel HTTP requests are blocked. It must not need a real Gemini key.
Revoke/rotate any real key previously copied into that test at the provider.

Demo seeders refuse to run outside `local` or `testing`. Before running
`php artisan db:seed` locally, set unique `SEED_ADMIN_PASSWORD` and
`SEED_DEMO_PASSWORD` values of at least 12 characters in your ignored `.env`.
Passwords have no shared fallback and are not printed. Configure these before
seeding; this cleanup does not modify an existing database or reset its accounts.
Use a separate, deliberate production account-provisioning process.

Do not commit `.env` files, provider credentials, operational logs or database
dumps. Historical copies remain until separately reviewed and cleaned.
