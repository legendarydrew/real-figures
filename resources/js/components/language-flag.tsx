import ReactCountryFlag from 'react-country-flag';
import { LanguageCountries } from '@/lib/language-codes';
import { useEffect, useState } from 'react';
import { useLanguages } from '@/context/language-context';

/**
 * A very simple component for displaying a representative country flag for a language.
 */

interface LanguageFlagProps {
    languageCode: string;
}

export const LanguageFlag: React.FC<LanguageFlagProps> = ({ languageCode }) => {

    const { matchingLanguage } = useLanguages();
    const [countryCode, setCountryCode] = useState<string | null>(null);

    useEffect(() => {
        setCountryCode(languageCode ? LanguageCountries[languageCode] : null);
    }, [languageCode]);

    return countryCode ? (
        <ReactCountryFlag svg countryCode={countryCode} title={matchingLanguage(languageCode)?.name}/>
    ) : null;
}
