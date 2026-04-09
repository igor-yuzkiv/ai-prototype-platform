<?php

namespace App\Handlers;

use App\Ai\Agents\PrototypePageImplementationAgent;
use App\Models\PrototypePageModel;

class GeneratePrototypePageHandler
{
    public function __invoke(PrototypePageModel $page): PrototypePageModel
    {

        $prompt = $this->builderPrompt($page);
        $response = (new PrototypePageImplementationAgent)
            ->prompt(
                prompt: $prompt,
                model: config('ai.models.code'),
            );

        $page->implementation = (string) $response;
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
Professional SaaS admin panel aesthetic. Clean, minimal, and functional.
Every page should feel like a real product — not a wireframe, not a student project.
Use whitespace deliberately. Avoid visual clutter.

## Layout
- Fixed top navigation bar present on every page.
- Main content area below the navbar with consistent padding.
- Group related content into cards or panels.
- Use sidebar layout where the page has navigation or filters on the left.

## Visual Hierarchy
- Clear distinction between page title, section headings, and body text.
- Important actions (primary buttons) must stand out visually.
- Secondary information should be visually subdued.
- Use font size, weight, and color — not decoration — to create hierarchy.

## Components Feel
- Buttons must look clickable and have hover states.
- Inputs and form fields must look clean and focused.
- Tables must have clear row separation and readable headers.
- Cards and panels must have visible but subtle borders or shadows.
- Status indicators (badges, tags) must use color meaningfully.

## Color and Typography
- Use a neutral, professional color palette unless globalRules specifies otherwise.
- Primary accent color for actions and links.
- Readable body font, consistent sizing across the page.
- Avoid bright or saturated colors unless used for status indicators.

## Quality Bar
- The result must look like something you would demo to a client.
- No raw unstyled HTML elements.
- No broken layouts.
- No placeholder lorem ipsum unless data structure is not provided.
- Every section must feel intentional and complete.
TEMPLATE;

    }
}
