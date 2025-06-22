import { Button } from '@/components/ui/button';
import { PlusIcon, XIcon } from 'lucide-react';
import { ChangeEvent, useEffect, useState } from 'react';
import HeadingSmall from '@/components/heading-small';
import { Textarea } from '@/components/ui/textarea';

interface ActMetaNotesProps {
    notes: {
        id: number;
        note: string;
    }[];
    onChange: (v) => void;
}

export const ActMetaNotes: React.FC<ActMetaNotesProps> = ({ notes, onChange }) => {

    const [rows, setRows] = useState([]);

    useEffect((): void => {
        setRows(notes ?? []);
    }, [notes]);

    const addRowHandler = (): void => {
        setRows((prev) => [...prev, { note: '' }]);
    };

    const removeRowHandler = (row): void => {
        const updatedRows = rows.filter((r) => r !== row);
        setRows(updatedRows);
        if (onChange) {
            onChange(rows);
        }
    };

    const updateRowHandler = (index: number, column: string, e: ChangeEvent): void => {
        const updatedRows = [...rows];
        updatedRows[index][column] = e.target.value;
        setRows(updatedRows);
        if (onChange) {
            onChange(updatedRows);
        }
    };

    return (
        <div>
            <HeadingSmall title="Act Notes"/>
            <ul className="my-2">
                {rows.map((row, index) => (
                    <li key={index} className="flex gap-2 my-1">
                        <Textarea className="flex-grow font-semibold text-xs" rows={3}
                                  placeholder="A note about the Act"
                                  value={row.note}
                                  onChange={(e) => updateRowHandler(index, 'note', e)}/>
                        <Button className="flex-shrink-0" size="icon" type="button" title="Remove"
                                onClick={() => removeRowHandler(row)}>
                            <XIcon/>
                        </Button>
                    </li>
                ))}
            </ul>
            <Button type="button" onClick={addRowHandler}>
                <PlusIcon/>
                Add
            </Button>

        </div>
    )
}
