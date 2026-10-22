---
name: vibe-workflow
description: >-
  Iterative vibe coding operating procedure based on Addy Osmani's Agent Skills (Spec-Driven Development, Atomic Slices, Systematic Debugging) with Headroom & RTK context optimization.
---

# Vibe Coding Workflow: Speed With Guardrails

Based on Addy Osmani's production agent methodology and token-efficient execution.

---

## 4-Stage Vibe Flow

```mermaid
flowchart LR
    A["1. Spec / Intent"] --> B["2. Atomic Slice"]
    B --> C["3. Fast Verification"]
    C --> D["4. Sync & Memory"]
```

### Stage 1: Spec / Intent (Define Before Code)
- Clarify what to build in 1-2 bullet points.
- Identify the affected files before opening or modifying them.
- Avoid broad file exploration when targeted edits suffice.

### Stage 2: Atomic Implementation (Thin Vertical Slice)
- Implement one clear piece of functionality at a time.
- Write drop-in replacements rather than rewriting entire files.
- Adhere strictly to the **Ponytail** solution ladder: standard library first, zero unrequested bloat.

### Stage 3: Fast Verification (Proof First)
- Run PHP syntax check immediately:
  `& "C:\Devaron\bin\php\php-8.3.36-Win32-vs16-x64\php.exe" -l <file.php>`
- Check database state if DB queries are touched:
  `& "C:\Devaron\bin\php\php-8.3.36-Win32-vs16-x64\php.exe" scripts/db_check.php`
- Never guess error causes—reproduce or read precise error logs first (Systematic Debugging).

### Stage 4: Sync & Memory Protocol
- Keep the project's long-term brain intact:
  1. Add record to MySQL:
     `& "C:\Devaron\bin\php\php-8.3.36-Win32-vs16-x64\php.exe" scripts/memory.php add --category=<cat> --title="<title>" --content="<content>"`
  2. Sync markdown:
     `& "C:\Devaron\bin\php\php-8.3.36-Win32-vs16-x64\php.exe" scripts/memory.php export-md`
  3. Update `CHANGELOG.md`.

---

## Token & Context Optimization Rules (Headroom / RTK Style)
1. **Targeted Reading**: Always use `StartLine` and `EndLine` when reading files; do not dump 500+ lines into the prompt context when only inspecting 30 lines.
2. **Compact Command Runs**: Avoid running commands that dump huge logs into output. Use specific filters, flags, or line count limits.
3. **No Redundant Repetition**: Do not restate unchanged code or repeat the prompt back to the user.
