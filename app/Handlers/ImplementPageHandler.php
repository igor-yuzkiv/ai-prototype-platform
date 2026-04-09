<?php

namespace App\Handlers;

use App\Ai\Agents\PrototypePageImplementationAgent;
use App\Models\PrototypePageModel;

class ImplementPageHandler
{
    public function __invoke(PrototypePageModel $page): PrototypePageModel
    {

        $prompt = $this->builderPrompt($page);
        $implementation = '';
        $stream = (new PrototypePageImplementationAgent)
            ->stream(
                prompt: $prompt,
                provider: config('ai.models.code.provider'),
                model: config('ai.models.code.model'),
            );

        foreach ($stream as $event) {
            logger($event);
            if (isset($event->delta)) {
                $implementation .= $event->delta;
            }
        }

        $page->implementation = $implementation;
        $page->save();

        return $page;
    }

    private function builderPrompt(PrototypePageModel $page): string
    {
        $prototype = $page->prototype;

        return <<<PROMPT
## [ projectOverview ]
{$prototype->project_overview}

## [ globalRules ]
{$prototype->global_rules}

## [ pageDescription ]
{$page->description}

## [ designTemplate ]
{$this->getDesignTemplate()}
PROMPT;
    }

    private function getDesignTemplate(): string
    {
        return <<<'TEMPLATE'
# Design Template

## Overall Style
Inspired by PrimeVue's design system. Clean, minimal, and refined.
Surfaces are light and airy. Components feel polished and intentional.
No heavy shadows, no gradients, no decorative elements.
The UI should feel modern, calm, and professional.

## Layout
- Fixed top navigation bar with a subtle bottom border, white background.
- Main content area with consistent horizontal padding and vertical spacing.
- Related content grouped into clean cards with minimal borders.
- Sidebar layouts use a clear left panel with soft separation from content.

## Visual Tone
- Lots of whitespace — breathing room between sections and components.
- Flat design with very subtle depth cues (thin borders, light shadows).
- Consistent corner radius across all components (inputs, buttons, cards).
- Muted secondary text for labels, metadata, captions.

## Components Feel
- Buttons: outlined or filled, rounded corners, no sharp edges, clear hover state.
- Inputs: thin border, rounded, clean focus ring, no heavy outlines.
- Tables: borderless rows with subtle dividers, light header background.
- Cards: white background, thin border or very soft shadow, generous inner padding.
- Badges and tags: small, pill-shaped, soft background colors, no hard contrast.
- Icons: simple line icons where applicable, consistent size.

## Color and Typography
- Neutral base palette: whites, light grays, soft blue-grays.
- Single primary accent color for actions, links, and active states (blue or indigo tone).
- Typography is clean and readable — no decorative fonts.
- Clear size hierarchy: page title > section heading > body > caption.
- Avoid pure black text — use dark gray for better readability.

## Quality Bar
- Every component must look intentional and finished.
- No raw unstyled HTML elements.
- No broken or misaligned layouts.
- The result must feel like a real PrimeVue-based product, not a generic Bootstrap page.
- Spacing, alignment, and consistency are as important as the components themselves.
TEMPLATE;

    }
}
