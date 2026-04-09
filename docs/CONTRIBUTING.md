# Contributing Guidelines

Thank you for considering contributing to Web Architect! This document outlines the process for contributing to the project.

## Code of Conduct

This project adheres to a code of conduct. By participating, you are expected to:

- Be respectful and inclusive
- Welcome newcomers and help them get started
- Focus on constructive criticism
- Accept responsibility and apologize for mistakes

Harassment or abusive behavior will not be tolerated.

---

## How to Contribute

### Types of Contributions

1. **Bug Reports** - Report errors or unexpected behavior
2. **Feature Requests** - Suggest new features
3. **Documentation** - Improve docs, add examples, fix typos
4. **Code Contributions** - Fix bugs, implement features
5. **Security Issues** - Report vulnerabilities privately

### Step by Step Process

#### 1. Check Existing Issues

Before starting work:
- Search [existing issues](https://github.com/your-org/Web-Architect/issues) to avoid duplicates
- Check [project board](https://github.com/your-org/Web-Architect/projects) for status

#### 2. Create an Issue (Recommended)

For non-trivial changes, create an issue first:

**Bug Report Template**:
```markdown
## Description
Clear description of the bug.

## Steps to Reproduce
1. Go to '...'
2. Click on '....'
3. See error

## Expected Behavior
What should happen?

## Screenshots
If applicable, add screenshots.

## Environment
- OS: [e.g. Windows 10, macOS 12]
- Browser: [e.g. Chrome 112]
- Laravel version: [e.g. 12.x]
- Vue version: [e.g. 3.x]

## Additional Context
Any other context about the problem.
```

**Feature Request Template**:
```markdown
## Problem Statement
What problem does this solve?

## Proposed Solution
Describe the solution you'd like.

## Alternatives Considered
Other approaches you've thought about.

## Mockups/Wireframes
If UI changes, attach designs.

## Impact
How many users will benefit? What's the priority?
```

#### 3. Fork the Repository

1. Click "Fork" button on GitHub
2. Clone your fork:
   ```bash
   git clone https://github.com/YOUR-USERNAME/Web-Architect.git
   cd Web-Architect
   ```

3. Add upstream remote:
   ```bash
   git remote add upstream https://github.com/your-org/Web-Architect.git
   ```

#### 4. Create a Branch

```bash
git checkout -b feature/amazing-feature
# or
git checkout -b fix/issue-123-bug
```

**Branch naming conventions**:
- `feature/short-description` - New features
- `fix/issue-number-description` - Bug fixes
- `docs/description` - Documentation changes
- `refactor/description` - Code restructuring
- `hotfix/description` - Production emergencies

#### 5. Make Changes

**Setup development environment** (see [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md))

**Make your changes** following:
- [Coding Standards](#coding-standards)
- [Project Architecture](ARCHITECTURE.md)
- [API Guidelines](API_DOCUMENTATION.md) if modifying APIs

#### 6. Write Tests

For new features or bug fixes:
- Add unit tests for core logic
- Add feature tests for HTTP endpoints
- Ensure all tests pass: `php artisan test`
- Aim for >85% coverage (required for new code)

#### 7. Update Documentation

If your changes affect:
- User-facing features → Update README.md or relevant docs in `docs/`
- API changes → Update [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
- New routes → Document in [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
- Database changes → Update [DATABASE.md](DATABASE.md)

#### 8. Code Quality Checks

```bash
# PHP formatting
./vendor/bin/pint

# Type checking (if using PHPStan)
composer require --dev phpstan/phpstan
./vendor/bin/phpstan analyse

# Frontend linting (if configured)
npm run lint

# All tests
php artisan test --coverage
```

#### 9. Commit Changes

Follow [Conventional Commits](https://www.conventionalcommits.org/):

```bash
git add .
git commit -m "feat(auth): add two-factor authentication

- Add OTP verification controller
- Update login flow to include OTP check
- Add email notification for OTP

Closes #123"
```

**Commit message guidelines**:
- Use imperative mood: "add", "fix", "update"
- Keep first line under 72 characters
- Use blank line between subject and body
- Reference issues with keywords: Closes, Fixes, Resolves

#### 10. Push to Your Fork

```bash
git push origin feature/amazing-feature
```

#### 11. Create Pull Request

1. Go to original repository on GitHub
2. Click "Compare & pull request"
3. Select your branch
4. Fill PR template:

```markdown
## Description
Brief description of changes.

## Type of Change
- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (would cause existing functionality to not work)
- [ ] Documentation update

## Testing
- [ ] Tests added/updated and passing
- [ ] Manual testing completed
- [ ] Test coverage for new code >85%

## Checklist
- [ ] Code follows PSR-12 standards
- [ ] No debug code left (dd(), dump(), var_dump())
- [ ] No sensitive data in code (.env, API keys)
- [ ] Database queries optimized
- [ ] Migrations included (if needed)
- [ ] Documentation updated
- [ ] Git commit messages follow convention

## Related Issues
Closes #123
Related to #456

## Screenshots
If UI changes, attach screenshots.
```

#### 12. Code Review

- Address review comments promptly
- Push additional commits to your branch (they'll automatically update the PR)
- Request re-review after changes
- Keep discussions focused on code quality

#### 13. Merge

Once approved:
- Maintainers will squash-merge your PR
- Delete your feature branch (optional)
- Pull latest `main` to your local:
  ```bash
  git checkout main
  git pull origin main
  ```

---

## Pull Request Review Criteria

Maintainers will check:

| Category | Requirements |
|----------|--------------|
| **Functionality** | Feature works as intended, no broken functionality |
| **Code Quality** | Follows PSR-12, clean, readable, efficient |
| **Security** | No SQL injection, XSS vulnerabilities, hardcoded secrets |
| **Performance** | No N+1 queries, optimized loops, proper indexing |
| **Tests** | Existing tests pass, new tests added (>85% coverage) |
| **Documentation** | README/API docs updated, inline comments where needed |
| **Backward Compatibility** | No breaking changes unless major version bump |
| **Dependencies** | No unnecessary new dependencies |

---

## Release Process

Maintainers only:

1. **Version bump** in `composer.json` and `package.json`
2. **Update CHANGELOG.md** with all merged PRs since last release
3. **Create GitHub Release** with release notes
4. **Tag release**: `git tag -a v1.2.0 -m "Release 1.2.0"`
5. **Push tags**: `git push origin --tags`
6. **Deploy to production**

---

## Security Reporting

**DO NOT open public issues for security vulnerabilities.**

Email security issues to: **security@webarchitect.com**

Include:
- Description of vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if any)

We will respond within 48 hours and aim to patch within 7 days.

---

## Communication Channels

- **GitHub Issues** - Bug reports, feature requests
- **GitHub Discussions** - Questions, ideas, general discussion
- **Slack/Discord** - Real-time chat with team
- **Email** - Security issues only

---

## Need Help?

- Read [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) for setup
- Check [FAQ.md](FAQ.md) for common questions
- Ask in GitHub Discussions
- Reach out to maintainers

---

## License

By contributing, you agree that your contributions will be licensed under the [MIT License](LICENSE).

---

**Thank you for contributing! 🎉**
