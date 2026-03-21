import { useEffect, useMemo, useState } from 'react'
import { Combobox, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/react'
import { cn } from '@/lib/utils';
import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import { XIcon } from 'lucide-react';

// Combobox adapted from https://tailwindcss.com/blog/headless-ui-v1-5.

interface ActMetaGenresProps {
    genres: string[]; // list of selected genres.
    genreList: string[]; // list of all available (saved) genres.
    className?: string;
    onChange: (v) => void;
}

export const ActMetaGenres: React.FC<ActMetaGenresProps> = ({ genres = [], genreList = [], className, onChange }) => {
    const [rows, setRows] = useState<string[]>([]);
    const [query, setQuery] = useState('');

    useEffect((): void => {
        setRows(genres ?? []);
    }, [genres]);

    const availableGenres: string[] = useMemo((): string[] => {
        return genreList.filter((genre) => !rows.includes(genre));
    }, [genreList, rows]);

    const filteredGenres = (): string[] => {
        if (query.trim() === '') {
            return availableGenres;
        }

        // If what's currently typed is not in the list, add it to the results.
        // This gives us the option to add new items, as well as provide typeahead functionality.
        const results: string[] = availableGenres.filter((genre) => {
            return genre.toLowerCase().includes(query.toLowerCase())
        });
        if (!genreList?.map((g) => g.toLowerCase()).includes(query.toLowerCase())) {
            results.push(query);
        }
        return results;
    }


    const selectHandler = (value: string): void => {
        if (value) {
            const updatedRows = [...new Set([...rows, value])];
            setRows(updatedRows);
            setQuery('');
            if (onChange) {
                onChange(updatedRows);
            }
        }
    };

    const removeRowHandler = (genre: string): void => {
        const updatedRows = rows.filter((r) => r !== genre);
        setRows(updatedRows);
        if (onChange) {
            onChange(updatedRows);
        }
    };

    return (
        <div>
            <HeadingSmall title="Act's Music Genres"/>
            <div className="flex flex-wrap gap-1 my-2 border rounded-sm p-1">
                {rows.map((genre) => (
                    <Button key={genre} className="font-sans flex-shrink-0 text-xs rounded-sm"
                            size="sm"
                            type="button"
                            title="Remove"
                            onClick={() => removeRowHandler(genre)}>
                        {genre}
                        <XIcon className="size-3" />
                    </Button>
                ))}

                <Combobox onChange={selectHandler}>
                    <ComboboxInput className={cn( "input-field", className)}
                                   value={query} onChange={(event) => setQuery(event.target.value)}/>
                    <ComboboxOptions
                        className="relative bg-popover text-popover-foreground data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 z-50 min-w-[8rem] overflow-hidden rounded-md border p-1 shadow-md">
                        {filteredGenres().map((genre) => (
                            <ComboboxOption key={genre} value={genre}
                                            className="data-[active]:bg-muted data-[active]:text-muted-foreground focus:bg-muted focus:text-muted-foreground [&_svg:not([class*='text-'])]:text-muted-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50 data-[inset]:pl-8 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4">
                                {genre}
                            </ComboboxOption>
                        ))}
                    </ComboboxOptions>
                </Combobox>
            </div>
        </div>
    );
}
