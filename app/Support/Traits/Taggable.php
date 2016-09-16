<?php

namespace Codice\Support\Traits;

use Auth;
use Codice\Label;

trait Taggable
{
    /**
     * Adds tags to taggable object and creates those tags if they are not extist yet.
     *
     * @param  array $tags Array of tags to add. Array value can be of type:
     *                      - integer: value of already-existing tag
     *                      - string: name of newly added tag (this is how select2.js behaves)
     * @return void
     */
    public function reTag(array $tags)
    {
        $existingTags = Label::orderBy('name')->lists('id')->toArray();

        foreach ($tags as $tagKey => $tagValue) {
            if (!in_array($tagValue, $existingTags)) {
                $newTag = Label::create([
                    'user_id' => Auth::id(),
                    'name' => $tagValue,
                ]);

                // We need to replace label name with its ID in $labels in order to get
                // Laravel's sync() working and happy...
                unset($tags[$tagKey]);
                $tags[] = $newTag->id;
            }
        }

        $this->labels()->sync($tags);
    }

    /**
     * Return entities with given tag.
     *
     * @param  int $tagID Tag ID
     * @return object
     */
    public static function tagged($tagID)
    {
        return self::whereHas('labels', function ($q) use ($tagID) {
            $q->where('id', $tagID);
        });
    }
}
