import { Button } from '@/components/ui/button';
import { PlusIcon, XIcon } from 'lucide-react';
import { Input } from '@/components/ui/input';
import { ChangeEvent, useEffect, useState } from 'react';
import HeadingSmall from '@/components/heading-small';

interface ActMetaMembersProps {
    members: {
        id: number;
        name: string;
        role: string;
    }[];
    onChange: (v) => void;
}

export const ActMetaMembers: React.FC<ActMetaMembersProps> = ({ members, onChange }) => {

    const [rows, setRows] = useState([]);

    useEffect((): void => {
        setRows(members ?? []);
    }, [members]);

    const addRowHandler = (): void => {
        setRows((prev) => [...prev, { name: '', role: '' }]);
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
            <HeadingSmall title="Act Members"/>
            <ul className="my-2">
                {rows.map((row, index) => (
                    <li key={row} className="flex gap-2 my-1">
                        <Input className="flex-grow font-semibold text-xs" placeholder="Name"
                               onChange={(e) => updateRowHandler(index, 'name', e)}/>
                        <Input className="flex-grow text-xs" placeholder="Role"
                               onChange={(e) => updateRowHandler(index, 'role', e)}/>
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
