<?php

namespace App\Services;

use App\Models\Round;
use App\Models\RoundOutcome;
use Illuminate\Support\Collection;

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
     * If ties are allowed, there may be more than one winner and more than the requested number
     * of runners-up.
     *
     * @param Round    $round
     * @param int|null $runner_up_count
     * @return Collection[]|null
     */
    public function calculate(Round $round, ?int $runner_up_count = null): ?array
    {
        $runner_up_count = $runner_up_count ?? config('contest.judgement.runners-up');
        $allow_ties = config('contest.judgement.allow-ties');

        if (!$this->isRoundEligible($round))
        {
            return null;
        }

        $results     = $this->ranked($round);
        $output      = [
            'winners'    => new Collection()
        ];
        $last_result = null;

        // First, let's determine which Songs have the highest score (as there might be a tie).
        foreach ($results as $index => $result)
        {
            if ($last_result && !$this->hasSameResult($result, $last_result))
            {
                break;
            }
            $output['winners']->push($result);
            $last_result = $result;
        }

        if ($allow_ties)
        {
            $results = isset($index) ? $results->slice($index) : new Collection();
        }
        else
        {
            // If there are tied results for the winners, the outright winner is determined at random.
            // The other entries will be first in line as runners-up.
            $winner            = $output['winners']->shuffle()->take(1);
            $output['winners'] = $winner;
            $results           = $results->filter(fn($result) => $result->song_id !== $winner->first()->song_id);
        }

        // Build a list of runners-up, up to the number requested.
        $output['runners_up'] = $this->pickRunnersUp($results, $runner_up_count);

        return $output;
    }

    protected function pickRunnersUp(Collection $results, int $count): Collection
    {
        $allow_ties = config('contest.judgement.allow-ties');

        $runners_up = new Collection();

        while ($runners_up->count() < $count && $results->isNotEmpty())
        {
            $entries = new Collection();
            foreach ($results as $result)
            {
                if ($entries->isEmpty() || $this->hasSameResult($entries->first(), $result))
                {
                    $entries->add($result);
                }
                else
                {
                    break;
                }
            }
            if ($allow_ties)
            {
                $runners_up = $runners_up->merge($entries);
            }
            else
            {
                // Similar to the winners: if there are tied runners-up, and ties aren't allowed,
                // the chosen ones are randomly selected.
                $runners_up = $runners_up->merge($entries->shuffle()->slice(0, $count - $runners_up->count()));
            }
            $results = $results->slice($entries->count());
        }

        return $runners_up;
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
            $this->intCompare($a->second_votes, $b->second_votes),
            $this->intCompare($a->third_votes, $b->third_votes)
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
