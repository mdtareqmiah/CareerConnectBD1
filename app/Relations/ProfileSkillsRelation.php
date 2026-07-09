<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileSkillsRelation extends HasMany
{
    public function __construct($query, $parent, $foreignKey, $localKey = null)
    {
        parent::__construct($query, $parent, $foreignKey, $localKey);
    }

    public function attach($id, array $attributes = []): void
    {
        $skill = $this->related->newQuery()->findOrFail($id);

        $payload = [
            'job_seeker_profile_id' => $this->parent->getKey(),
            'skill_name' => $skill->getAttribute('skill_name') ?: $skill->getAttribute('name') ?: null,
            'proficiency_level' => $attributes['proficiency_level'] ?? $skill->getAttribute('proficiency_level') ?? 'Beginner',
            'years_of_experience' => $attributes['years_of_experience'] ?? $skill->getAttribute('years_of_experience') ?? null,
            'notes' => $attributes['notes'] ?? $skill->getAttribute('notes') ?? null,
        ];

        $skill->fill($payload);
        $skill->save();
    }
}
