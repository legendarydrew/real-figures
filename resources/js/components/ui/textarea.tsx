import * as React from "react"
import { ChangeEvent } from "react"

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
    const changeResponseHandler = (e: ChangeEvent): void => {
        e.target.parentNode.dataset.clonedVal = e.target.value;
    }

    return (
        <div className={cn('textarea-expand', className)}>
            <textarea
                data-slot="input"
                className="input-field"
                onInput={changeResponseHandler}
                {...props}
            />
        </div>
    )
}

export { Textarea, ExpandingTextarea }
