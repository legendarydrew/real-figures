import React, { useState } from 'react';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';

interface Props {
    stages: { id: number, name: string, status: string }[];
    onChange: (id: number) => void;
}

export const NewsStageSelect: React.FC<Props> = ({
                                                     stages, onChange = (id) => {
    }
                                                 }) => {

    const [selected, setSelected] = useState<number>();

    const stageLabel = (stageId: number) => {
        const matchingStage = stages.find((stage) => stage.id == stageId);
        return matchingStage ? (<span>
            {matchingStage.title} <Badge>{matchingStage.status}</Badge>
        </span>) : 'none';
    };

    const selectHandler = (id: number): void => {
        setSelected(id);
        onChange(id);
    };

    return (
        <section>
            <Label className="sr-only" htmlFor="postStageReference">Select a Stage</Label>
            <Select id="postStageReference" onValueChange={selectHandler}>
                <SelectTrigger>
                    {selected ? stageLabel(selected) : 'Select a Stage...'}
                </SelectTrigger>
                <SelectContent>
                    {stages.map((stage) => (
                        <SelectItem key={stage.id} value={stage.id}>
                            {stageLabel(stage.id)}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
        </section>
    );
};
