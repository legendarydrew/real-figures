import * as React from "react"
import { ChangeEvent, useEffect, useRef } from "react"

import { cn } from "@/lib/utils"

function Textarea({ className, ...props }: React.ComponentProps<"textarea">) {
    return (
        <textarea
            data-slot="input"
            className={cn('input-field', className)}
            {...props}
        />
    )
}

const ExpandingTextarea: React.FC = ({ className, ...props }: React.ComponentProps<"textarea">) => {

    const textareaRef = useRef<HTMLTextAreaElement|null>(null);

    useEffect(() => {
        if (textareaRef.current) {
            textareaRef.current.parentNode.dataset.clonedVal = props.value;
        }
    }, [props.value]);

    const changeResponseHandler = (e: ChangeEvent): void => {
        if (textareaRef.current) {
            textareaRef.current.parentNode.dataset.clonedVal = e.target.value;
        }
    }

    return (
        <div className={cn('textarea-expand', className)}>
            <textarea
                ref={textareaRef}
                data-slot="input"
                className={cn("input-field", className)}
                onInput={changeResponseHandler}
                {...props}
            />
        </div>
    )
}

export { Textarea, ExpandingTextarea }
