# Unified Lead Form Design

## Goal

Use one Vue lead form for the homepage consultation panel and floating contact modal, styled after the local Irsko.ie redesign.

## Layouts

- The homepage uses a dark patterned two-column panel, a local brochure image, and a white inline form card.
- The floating launcher opens the same patterned visual treatment in an accessible modal.
- The inline form shows name, email, phone, program selector, message, consent, and the consultation CTA.
- The compact modal shows name, email, message, consent, and the same CTA.

## Behavior

`contact-form.vue` owns validation, submission, errors, loading state, confirmation, page attribution, and consent for both layouts. The current `/contact` endpoint, lead review workflow, CRM delivery, source attribution, and rate limiting remain unchanged.

The program selector is UI context only: selecting a program prepopulates the existing message field rather than changing the lead schema.

The modal closes with Escape, close control, or backdrop; it locks page scrolling while open and exposes dialog semantics.

## Tests

- Assert the shared form exposes inline and modal variants.
- Assert the homepage renders the reference-style panel, local image, and inline variant.
- Assert the floating modal renders the compact variant and remains hidden before Vue mounts.
