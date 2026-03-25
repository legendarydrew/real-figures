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

export const ActMetaMembers: React.FC<ActMetaMembersProps> = ({
                                                                  members, onChange = () => {
    }
                                                              }) => {

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
        onChange(rows);
    };

    const updateRowHandler = (index: number, column: string, e: ChangeEvent): void => {
        const updatedRows = [...rows];
        updatedRows[index][column] = e.target.value;
        setRows(updatedRows);
        onChange(updatedRows);
    };

    return (
        <div>
            <HeadingSmall title="Act Members"/>
            <div className="border rounded-sm p-1">
                <ul className="flex flex-col">
                    {rows.map((row, index) => (
                        <li key={index} className="flex gap-x-2 gap-y-1 mb-1">
                            <Input className="flex-grow font-semibold text-xs" placeholder="Name"
                                   value={row.name}
                                   onChange={(e) => updateRowHandler(index, 'name', e)}/>
                            <Input className="flex-grow text-xs" placeholder="Role"
                                   value={row.role}
                                   onChange={(e) => updateRowHandler(index, 'role', e)}/>
                            <Button className="font-sans flex-shrink-0 text-xs rounded-sm p-2" size="icon" type="button"
                                    title="Remove"
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

        </div>
    )
}
