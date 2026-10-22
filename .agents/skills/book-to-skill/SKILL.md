---
name: book-to-skill
description: >-
  Converts technical books, architecture manuals, design guidelines, or PDF documentation into structured, progressive agent skills for Google Antigravity.
---

# Book-To-Skill — Knowledge to Agent Engine

Inspired by `virgiliojr94/book-to-skill`. Converts large reference materials, API documentation, or books into portable, on-demand Antigravity agent skills (`SKILL.md`) using progressive disclosure.

---

## Why Progressive Disclosure?
Dumping an entire 200-page manual or library documentation into the prompt context wastes thousands of tokens and causes attention dilution. Book-to-skill splits knowledge into:
1. **Root Router (`SKILL.md`)**: Overview, glossary index, decision rules, and quick reference matrices.
2. **On-Demand Submodules (`modules/` or `chapters/`)**: Deep-dive operational instructions read only when specifically referenced.

---

## Skill Compilation Process

### Step 1: Ingest & Extract
Scan the source document (Markdown, PDF, HTML, or text) to extract:
- **Core Mental Models**: The primary rules and abstractions.
- **Decision Matrices**: When to use pattern A vs pattern B.
- **Code Snippets & Recipes**: Canonical implementation examples.
- **Anti-Patterns / Gotchas**: What to avoid.

### Step 2: Structure as Antigravity Skill
Create the folder inside `.agents/skills/<skill-name>/`:
- `SKILL.md`: Frontmatter with `name` and `description` + summary guide.
- `patterns.md`: Extracted code patterns and recipes.
- `gotchas.md`: Anti-patterns and security pitfalls.

### Step 3: Progressive Activation
When the user asks for guidance related to that domain, Antigravity loads only the metadata, and reads the targeted submodule on demand.
