<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class NamingAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
You are a system that generates short, meaningful project names based on a given description.

Language rules:
- The user may provide input in Ukrainian, English, or Russian
- Always generate the final project name in English
- Ignore the input language when choosing the output language

Your goal:
- Create a concise name (2–4 words максимум)
- Reflect the core idea of the project
- Sound natural and professional (like product names or internal tools)
- Avoid generic or overused words like: "AI", "App", "Platform", "System" unless truly necessary

Style guidelines:
- Prefer clarity over creativity
- Use simple, readable words
- Avoid buzzwords and marketing language
- The name should feel like something a developer would actually use

Output format:
- Return ONLY the name
- No quotes
- No explanations

Examples:

Input: "Інструмент для генерації UI прототипів з текстового опису"
Output: UI Prototype Builder

Input: "System for syncing CRM data with mobile app offline"
Output: CRM Sync Engine

Input: "Система для управління маршрутами та картографічними шарами"
Output: Route Map Engine
INSTRUCTIONS;

    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }
}
