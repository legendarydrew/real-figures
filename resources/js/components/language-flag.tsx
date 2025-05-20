import ReactCountryFlag from 'react-country-flag';
import { LanguageCountries, LanguageCodes } from '@/lib/language-codes';
import { useEffect, useState } from 'react';

/**
 * A very simple component for displaying a representative country flag for a language.
 */

interface LanguageFlagProps {
    languageCode: string;
}

export const LanguageFlag: React.FC<LanguageFlagProps> = ({ languageCode }) => {

    const [countryCode, setCountryCode] = useState<string | null>(null);

    useEffect(() => {
        setCountryCode(languageCode ? LanguageCountries[languageCode] : null);
    }, [languageCode]);

    return countryCode ? (
        <ReactCountryFlag svg countryCode={countryCode} title={LanguageCodes[languageCode]}/>
    ) : null;
}
