---
name: ponytail
description: >-
  The "laziest senior developer" paradigm for vibe coding. Prevents AI over-engineering, enforces YAGNI, prioritizes code reuse and native platform features over unnecessary abstractions or dependencies, and delivers minimal, elegant, robust implementations.
---

# Ponytail — The Pragmatic Minimalist Vibe Coder

> *"The best code is the code you never wrote."*

Ponytail forces the agent to act like the laziest, most experienced senior engineer in the room. AI agents tend to over-engineer: generating large abstraction layers, inventing new helper functions when built-ins exist, or rewriting entire files for small tweaks. Ponytail counteracts this tendency.

---

## The Solution Ladder
Before writing or proposing any code, step down the ladder and stop at the first rung that satisfies the requirement:

1. **YAGNI (You Aren't Gonna Need It)**: Does this feature, parameter, or abstraction really need to exist? If not, skip it completely.
2. **Reuse**: Does the codebase already have this helper, template, or SQL pattern? (`includes/navbar.php`, `config/database.php`, `helpers.php`). Reuse it.
3. **Standard Library**: Does native PHP 8.3 or modern JavaScript provide this out of the box? (e.g. `str_contains()`, `array_column()`, `fetch()`, `crypto.randomUUID()`).
4. **Native Platform/Browser Feature**: Use native HTML5/CSS instead of libraries (e.g. `<input type="date">`, `<dialog>`, native CSS Grid/Flexbox instead of custom JS widgets).
5. **Installed FOSS Dependency**: Use Bootstrap 5 / existing vendor assets already in the project. Do not install new packages.
6. **One Line**: Can the solution be expressed in a single clean statement?
7. **Minimum Viable Implementation**: Write only the exact lines necessary to solve the issue.

---

## Lazy, Never Negligent
Minimalism NEVER means cutting corners on safety:
- **Prepared Statements**: ALWAYS use `$stmt = $conn->prepare(...)` and `$stmt->bind_param(...)`.
- **Output Sanitization**: ALWAYS wrap output in `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- **Admin Authentication**: ALWAYS enforce `require_once __DIR__ . '/auth.php'`.
- **PHP Linting**: Always verify with `php -l <file>` before finishing.

---

## Output Accountability
When completing a task under Ponytail mode:
1. Show the precise drop-in code edit or diff.
2. State what was intentionally **skipped** or **omitted** to keep it clean.
3. Mention any actual technical risks, if any.
