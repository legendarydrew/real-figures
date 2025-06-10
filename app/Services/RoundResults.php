<?php

namespace App\Services;

use App\Models\Round;
use App\Models\RoundOutcome;
use Illuminate\Database\Eloquent\Collection;

class RoundResults
{
    /**
     * Returns TRUE if the specified Round should have results calculated.
     *
     * @param Round $round
     * @return bool
     */
    protected function isRoundEligible(Round $round): bool
    {
        return $round->hasEnded() && $round->outcomes()->count();
    }

    /**
     * Returns the outcomes of the specified Round in ranked order.
     * If the Round has not yet ended, or there are no associated outcomes, nothing is returned.
     */
    public function ranked(Round $round): ?Collection
    {
        if ($this->isRoundEligible($round))
        {
            return $round->outcomes()->get()
                         ->sort(fn($a, $b) => self::sortOutcomes($a, $b))
                         ->values(); // a nasty gotcha - otherwise the keys are preserved.
        }
        return null;
    }

    /**
     * Returns a list of winning and runner-up RoundOutcomes for the specified Round.
     * If the Round has not yet ended, or there are no associated outcomes, nothing is returned.
     * At present, we are allowing for ties.
     *
     * @param Round    $round
     * @param int|null $runner_up_count
     * @return Collection[]|null
     */
    public function calculate(Round $round, ?int $runner_up_count): ?array
    {
        if (is_null($runner_up_count)) {
            $runner_up_count = config('contest.judgement.runners-up');
        }

        if ($this->isRoundEligible($round))
        {
            $results     = $this->ranked($round);
            $output      = [
                'winners'    => new Collection(),
                'runners_up' => new Collection(),
            ];
            $last_result = null;

            // Build a list of winning entries.
            // There may be more than one, based on the scores and votes.
            foreach ($results as $index => $result)
            {
                if ($last_result && !$this->hasSameResult($result, $last_result))
                {
                    break;
                }
                $output['winners']->push($result);
                $last_result = $result;
            }

            // Build a list of runners-up, up to the number requested.
            $results     = $results->slice($index);
            $last_result = null;
            foreach ($results as $result)
            {
                if ($output['runners_up']->count() === $runner_up_count ||
                    ($last_result && !$this->hasSameResult($result, $last_result)))
                {
                    break;
                }
                $output['runners_up']->push($result);
                $last_result = $result;
            }

            return $output;
        }

        return null;
    }

    /**
     * A callback function for sorting RoundOutcomes.
     * The entries are ranked in descending order, by:
     * - total score;
     * - number of first choice votes;
     * - number of second choice votes.
     *
     * @param RoundOutcome $a
     * @param RoundOutcome $b
     * @return int
     */
    protected function sortOutcomes(RoundOutcome $a, RoundOutcome $b): int
    {
        $values = array_filter([
            $this->intCompare($a->score, $b->score),
            $this->intCompare($a->first_votes, $b->first_votes),
            $this->intCompare($a->second_votes, $b->second_votes)
        ]);

        return count($values) ? array_values($values)[0] : 0;
    }

    /**
     * A convenience function for returning a value for sorting based on two integers.
     * Returns a value used for sorting a list of integers in ascending order.
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    protected function intCompare(int $a, int $b): int
    {
        if ($a === $b)
        {
            return 0;
        }
        else
        {
            return $a < $b ? 1 : -1;
        }
    }

    /**
     * Returns TRUE if the specified RoundOutcomes have exactly the same score and vote counts.
     *
     * @param RoundOutcome $a
     * @param RoundOutcome $b
     * @return bool
     */
    protected function hasSameResult(RoundOutcome $a, RoundOutcome $b): bool
    {
        return ($a->score === $b->score) &&
            ($a->first_votes === $b->first_votes) &&
            ($a->second_votes === $b->second_votes) &&
            ($a->third_votes === $b->third_votes);
    }

}
