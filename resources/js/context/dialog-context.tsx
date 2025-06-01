/**
 * Solving the problem of being able to open a single dialog from anywhere in the app.
 * ChatGPT suggested using a context, where the open dialog state can be accessed
 * by other components inside the context.
 */
import { createContext, useContext, useMemo, useState } from "react";

const DialogContext = createContext();

export function DialogProvider({ children }) {
    const [openDialogName, setOpenDialogName] = useState<string>(null);
    const [dialogProps, setDialogProps] = useState<{ [key: string]: never }>({});

    const openDialog = (name: string, props?: { [key: string]: never }) => {
        setOpenDialogName(name);

        // Thinking about how to open a dialog with specific settings.
        // Why not use a hook?
        if (props) {
            setDialogProps(props);
        }
    };

    const closeDialog = () => setOpenDialogName(null);

    // SonarLint suggests using useMemo() for context values, to avoid unnecessary rendering.
    // useMemo() stores values in memory; useCallback() should be used for storing functions.
    const providerValues = useMemo(() => ({ openDialogName, openDialog, closeDialog, dialogProps }), [openDialogName]);

    return (
        <DialogContext.Provider value={providerValues}>
            {children}
        </DialogContext.Provider>
    );
}

export function useDialog() {
    // A custom hook for accessing the context.
    return useContext(DialogContext);
}
