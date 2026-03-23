import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import React, { useState } from 'react';

interface Props {
    rounds: { id: number; title: string; }[];
    onChange: (id: number) => void;
}

export const NewsRoundSelect: React.FC<Props> = ({ rounds, onChange }) => {
    const [selected, setSelected] = useState<number>();

    const roundLabel = (roundId: number) => {
        const matchingRound = rounds.find((round) => round.id == roundId);
        return matchingRound ? (<span>{matchingRound.title}</span>) : 'none';
    };

    const selectHandler = (id: number): void => {
        setSelected(id);
        onChange(id);
    };

    return (
        <section>
            <Label className="sr-only" htmlFor="postRound">Select a Round</Label>
            <Select id="postRound" onValueChange={selectHandler}>
                <SelectTrigger>{selected ? roundLabel(selected) : 'Select a Round...'}</SelectTrigger>
                <SelectContent>
                    {rounds.map((round) => (
                        <SelectItem key={round.id} value={round.id}>
                            {roundLabel(round.id)}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
        </section>
    );
};
