# Contributing

Thanks for helping to improve Web-Safe Fonts for Editor.

## Issues

- **Bugs:** use the bug report template and include your WordPress, PHP and plugin versions.
- **Ideas:** use the feature request template. The plugin stays small on purpose, so features outside its scope may
  be declined.
- **Security issues:** follow [SECURITY.md](SECURITY.md), never open a public issue.

## Pull requests

1. Fork the repository and create a branch from `main`.
2. Install the development tools: `composer install`.
3. Keep the change focused: one fix or feature per pull request.
4. Run the checks: `composer lint` and `composer test`.
5. Open the pull request against `main` and describe what changed and why.

The code follows the WordPress Coding Standards and must run on PHP 7.4 and later. User-facing text is translatable
with the `web-safe-fonts-for-editor-by-rotistudio` text domain.

Edit `readme.txt`, not `README.md`: README.md is generated from readme.txt when a release is prepared.

By contributing you agree that your contribution is licensed under the project's GPL-2.0-or-later license.
