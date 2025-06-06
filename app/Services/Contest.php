<?php

namespace App\Services;

use App\Models\Stage;

class Contest
{

    /**
     * Returns TRUE if the contest is over.
     * The contest is considered over if all Stages are over.
     *
     * @return bool
     */
    public function isOver(): bool
    {
        $stages = Stage::all();
        return $stages->isNotEmpty() && $stages->every(fn(Stage $stage) => $stage->isOver());
    }

    /**
     * Returns the currently active Stage, if one is available.
     *
     * @return Stage|null
     */
    public function getCurrentStage(): Stage|null
    {
        $stages         = Stage::all();
        $previous_stage = null;
        foreach ($stages as $stage)
        {
            if ($stage->isInactive())
            {
                // The Stage has no Rounds - go no further.
                return $previous_stage;
            }
            elseif ($stage->isOver())
            {
                // The current Stage will be the last "over" Stage.
                // This would occur if we want to display the winners of the last Stage.
                $previous_stage = $stage;
            }
            else
            {
                // Any of the other states.
                return $stage;
            }
        }

        return $previous_stage;
    }

    /**
     * Returns TRUE if the current Stage is also the last Stage.
     * Used for identifying the final.
     *
     * @return bool
     */
    public function isOnLastStage(): bool
    {
        $current_stage = $this->getCurrentStage();
        $last_stage    = Stage::orderByDesc('id')->first();

        return $current_stage->id === $last_stage->id;
    }

}
