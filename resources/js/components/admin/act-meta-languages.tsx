import { Button } from '@/components/ui/button';
import { XIcon } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import HeadingSmall from '@/components/heading-small';
import { LanguageFlag } from '@/components/language-flag';
import { LanguageCodes } from '@/lib/language-codes';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';

interface ActMetaLanguagesProps {
    languages: string[];
    onChange: (v) => void;
}

export const ActMetaLanguages: React.FC<ActMetaLanguagesProps> = ({ languages, onChange }) => {

    const [rows, setRows] = useState([]);

    useEffect((): void => {
        setRows(languages ?? []);
    }, [languages]);

    const availableLanguages = useMemo((): string[] => {
        return Object.keys(LanguageCodes).filter((languageCode) => !rows.includes(languageCode));
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
                        {LanguageCodes[languageCode]}
                        <XIcon/>
                    </Button>
                ))}

                {availableLanguages.length ? (
                    <Select id="songLanguage" onValueChange={addLanguageHandler}>
                        <SelectTrigger className="w-auto">Add language</SelectTrigger>
                        <SelectContent>
                            {availableLanguages.map((languageCode) => (
                                <SelectItem key={languageCode} value={languageCode}>
                                    <LanguageFlag languageCode={languageCode}/>
                                    {LanguageCodes[languageCode]}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                ) : ''}
            </div>

        </div>
    )
}
