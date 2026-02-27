import * as React from "react"
import * as CheckboxPrimitive from "@radix-ui/react-checkbox"
import { CheckIcon } from "lucide-react"

import { cn } from "@/lib/utils"

function Checkbox({
                      className,
                      ...props
                  }: React.ComponentProps<typeof CheckboxPrimitive.Root>) {
    return (
        <CheckboxPrimitive.Root
            data-slot="checkbox"
            className={cn("checkbox peer", className)}
            {...props}
        >
            <CheckboxPrimitive.Indicator data-slot="checkbox-indicator" className="checkbox-indicator">
                <CheckIcon className="checkbox-indicator-icon"/>
            </CheckboxPrimitive.Indicator>
        </CheckboxPrimitive.Root>
    )
}

export { Checkbox }
