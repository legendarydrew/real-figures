import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { Square, SquareCheck } from 'lucide-react';
import React, { useState } from 'react';

interface Props {
    acts: { id: number, name: string, subtitle?: string }[];
    onChange: (ids: number[]) => void;
}

export const NewsActSelect: React.FC<Props> = ({
                                                   acts, onChange = (ids) => {
    }
                                               }) => {

    const [selection, setSelection] = useState<number[]>([]);

    const selectHandler = (actId: number, state: boolean): void => {
        let newSelection = [...selection];
        if (state) {
            newSelection.push(actId);
        } else {
            newSelection = newSelection.filter((id) => id !== actId);
        }
        newSelection = [...new Set(newSelection)];
        setSelection(newSelection);

        onChange(newSelection);
    };

    const selectAllHandler = (): void => {
        setSelection(acts.map((act) => act.id));
    };

    const selectNoneHandler = (): void => {
        setSelection([]);
    };

    return (
        <section>
            <div className="flex gap-1 mb-2 items-center">
                <div className="font-semibold text-sm flex-grow">Select one or more Acts...</div>
                <Button type="button" size="icon" className="p-2" title="Select all" onClick={selectAllHandler}>
                    <SquareCheck className="size-4"/>
                </Button>
                <Button type="button" size="icon" className="p-2" title="Select none" onClick={selectNoneHandler}>
                    <Square className="size-4"/>
                </Button>
            </div>

            <div className="grid gap-3 grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
                {acts.map((act) => (
                    <Label key={act.id} className="display-text">
                        <div className="flex items-center gap-1 leading-none">
                            <Checkbox value={act.id}
                                      checked={selection.includes(act.id)}
                                      onCheckedChange={(state) => selectHandler(act.id, state)}/>
                            {act.name} <span
                            className="text-xs text-muted-foreground leading-none">{act.subtitle}</span>
                        </div>
                    </Label>
                ))}
            </div>
        </section>

    );
}
