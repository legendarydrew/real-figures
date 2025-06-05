<?php

namespace App\Services;

use App\Models\Stage;

class Contest
{

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

}
