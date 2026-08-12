# IRSKO STUDY Favicon Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a small orange study accent to the existing green IRSKO favicon.

**Architecture:** Keep the parent brand’s circular green mark and dark IRSKO glyph. Add one orange graduation-cap accent in the SVG, then rasterize that SVG into the ICO and PNG variants already linked by the Blade layout.

**Tech Stack:** SVG, ImageMagick, Laravel Blade, PHPUnit.

## Global Constraints

- Change favicon assets only; do not alter the navigation or site logo.
- Preserve the existing green circle and dark IRSKO wordmark.
- Use the existing orange brand color `#F58320` for the study accent.

---

### Task 1: Build the sub-brand favicon assets

**Files:**
- Modify: `public/img/svg/favicon.svg`
- Modify: `public/favicon.ico`
- Modify: `public/img/svg/favicon.ico`
- Modify: `public/img/svg/favicon-16x16.png`
- Modify: `public/img/svg/favicon-32x32.png`
- Modify: `public/img/svg/apple-touch-icon.png`
- Modify: `public/img/svg/android-chrome-192x192.png`
- Modify: `public/img/svg/android-chrome-512x512.png`

- [ ] Add an orange graduation-cap accent above the dark IRSKO wordmark in `favicon.svg`, preserving legibility at 16px.
