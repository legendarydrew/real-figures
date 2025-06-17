import { Button } from '@/components/ui/button';
import { XIcon } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import HeadingSmall from '@/components/heading-small';
import { LanguageFlag } from '@/components/language-flag';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import { useLanguages } from '@/context/language-context';
import { LanguageRow } from '@/types';

interface ActMetaLanguagesProps {
    languages: string[];
    onChange: (v) => void;
}

export const ActMetaLanguages: React.FC<ActMetaLanguagesProps> = ({ languages, onChange }) => {

    const { languageList, matchingLanguage } = useLanguages();
    const [rows, setRows] = useState<string[]>([]);

    useEffect((): void => {
        setRows(languages ?? []);
    }, [languages]);

    const availableLanguages: LanguageRow[] = useMemo((): { code: string, name: string }[] => {
        return languageList.current.filter((languageCode) => !rows.includes(languageCode));
    }, [rows]); /* we're learning! */

    const addLanguageHandler = (languageCode: string): void => {
        const updatedRows = [...new Set([...rows, languageCode])];
        setRows(updatedRows);
        if (onChange) {
            onChange(updatedRows);
        }
    };

    const removeRowHandler = (languageCode: string): void => {
        const updatedRows = rows.filter((r) => r !== languageCode);
        setRows(updatedRows);
        if (onChange) {
            onChange(updatedRows);
        }
    };

    return (
        <div>
            <HeadingSmall title="Act's Spoken Languages"/>
            <div className="flex flex-wrap gap-2 my-2">
                {rows.map((languageCode) => (
                    <Button key={languageCode} className="flex-shrink-0 rounded-md" variant="outline" size="sm"
                            type="button"
                            title="Remove"
                            onClick={() => removeRowHandler(languageCode)}>
                        <LanguageFlag languageCode={languageCode}/>
                        {matchingLanguage(languageCode)?.name}
                        <XIcon/>
                    </Button>
                ))}

                {availableLanguages.length ? (
                    <Select id="songLanguage" onValueChange={addLanguageHandler}>
                        <SelectTrigger className="w-auto">Add language</SelectTrigger>
                        <SelectContent>
                            {availableLanguages.map((language) => (
                                <SelectItem key={language.code} value={language.code}>
                                    <LanguageFlag languageCode={language.code}/>
                                    {matchingLanguage(language.code)?.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                ) : ''}
            </div>

        </div>
    )
}
