# Changelog

## [0.9.5] - 2026-07-02

- **chore:** Update `config/app.php` to support language configuration.

## [0.9.4] - 2026-06-22

- **chore:** Relax framework constraint from `^0.9` to `~0.9` so new projects automatically get framework v0.10.0 and future 0.x releases without updating the template.

## [0.9.3] - 2026-06-14

- **chore:** Update `.gitignore` to ignore env variants, editor configs, secrets, and logs.

## [0.9.2] - 2026-05-24

- **feat:** Add `app/helpers.php` with typed `user()` helper for IDE/static-analysis support.
- **chore:** Autoload `app/helpers.php` via `composer.json`.
- **chore:** Add `app/Tools` and `app/Transformers` directories.
- **chore:** Update `.gitattributes` to exclude `CHANGELOG.md` from export archives.
- **fix:** Clean `.env.example` defaults.