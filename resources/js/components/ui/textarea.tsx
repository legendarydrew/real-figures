import * as React from "react"

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

export { Textarea }
