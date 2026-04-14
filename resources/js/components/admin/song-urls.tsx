import { Button } from '@/components/ui/button';
import { PlusIcon, XIcon } from 'lucide-react';
import { ChangeEvent, useEffect, useState } from 'react';
import { Input } from '@/components/ui/input';

interface ActMetaTraitsProps {
    urls: {
        id: number;
        url: string;
    }[];
    onChange: (v) => void;
}

export const SongUrls: React.FC<ActMetaTraitsProps> = ({ urls, onChange }) => {

    const [rows, setRows] = useState([]);

    useEffect((): void => {
        setRows(urls ?? []);
    }, [urls]);

    const addRowHandler = (): void => {
        setRows((prev) => [...prev, { url: '' }]);
    };

    const removeRowHandler = (row): void => {
        const updatedRows = rows.filter((r) => r !== row);
        setRows(updatedRows);
        if (onChange) {
            onChange(updatedRows);
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
            <ul className="my-2">
                {rows.map((row, index) => (
                    <li key={index} className="flex items-stretch my-1">
                        <Input className="flex-grow text-xs"
                               placeholder="YouTube video embed URL"
                               value={row.url}
                               onChange={(e) => updateRowHandler(index, 'url', e)}/>
                        <Button className="flex-shrink-0" size="icon" type="button" title="Remove"
                                onClick={() => removeRowHandler(row)}>
                            <XIcon className="size-3"/>
                        </Button>
                    </li>
                ))}
            </ul>
            <Button type="button" size="sm" onClick={addRowHandler}>
                <PlusIcon/>
                Add
            </Button>

        </div>
    )
}
