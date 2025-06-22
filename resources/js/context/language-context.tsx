/**
 * We wanted to fetch a list of languages from the API just once, for use wherever we want to
 * make use of language codes and names. Originally I had opted to use a hook, but the
 * preferred way is to use (another) context.
 */
import { createContext, useCallback, useContext, useEffect, useMemo, useRef, useState } from "react";
import axios from 'axios';
import { LanguageRow } from '@/types';

interface LanguageContextValue {
    languageList: React.RefObject<LanguageRow[]>;
    matchingLanguage: (code: string) => LanguageRow | undefined;
}

const LanguageContext = createContext<LanguageContextValue|undefined>(undefined);

export function LanguageProvider({ children }) {

    const [isLoading, setIsLoading] = useState<boolean>(false);

    // The list of languages (code and name).
    const languageList = useRef<LanguageRow[]>([]);

    // A method to obtain a matching language entry from a language code.
    // The result will always be the same, hence the use of useCallback().
    const matchingLanguage = useCallback((code: string): LanguageRow | undefined => languageList.current.find((l) => l.code === code), [languageList]);

    const providerValues: LanguageContextValue = useMemo(() => ({ languageList, matchingLanguage }), [languageList, matchingLanguage]);

    useEffect(() => {
        // Fetch the list of languages from the API endpoint.
        if (!(isLoading || languageList.current.length)) {
            setIsLoading(true);
            axios.get(route('languages'))
                .then((response) => {
                    languageList.current = response.data;
                })
                .finally(() => {
                    setIsLoading(false);
                })
        }
    }, []);

    return (
        <LanguageContext.Provider value={providerValues}>
            {children}
        </LanguageContext.Provider>
    );
}

export function useLanguages() {
    // A custom hook for accessing the context.
    const context = useContext(LanguageContext);
    if (!context) {
        throw new Error("useLanguages must be used within a LanguageProvider.");
        // Thank you ChatGPT!
    }
    return context;
}
