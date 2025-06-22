import { Button } from '@/components/ui/button';
import { PlusIcon, XIcon } from 'lucide-react';
import { ChangeEvent, useEffect, useState } from 'react';
import HeadingSmall from '@/components/heading-small';
import { Input } from '@/components/ui/input';

interface ActMetaTraitsProps {
    traits: {
        id: number;
        trait: string;
    }[];
    onChange: (v) => void;
}

export const ActMetaTraits: React.FC<ActMetaTraitsProps> = ({ traits, onChange }) => {

    const [rows, setRows] = useState([]);

    useEffect((): void => {
        setRows(traits ?? []);
    }, [traits]);

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
            <HeadingSmall title="Act Traits"/>
            <ul className="my-2">
                {rows.map((row, index) => (
                    <li key={index} className="flex gap-2 my-1">
                        <Input className="flex-grow text-xs" rows={3}
                               placeholder="A personality trait"
                               value={row.trait}
                               onChange={(e) => updateRowHandler(index, 'trait', e)}/>
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
