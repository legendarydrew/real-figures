import { PaginatedResponse } from '@/types';
import { Button } from '@/components/ui/button';
import React, { useEffect, useState } from 'react';
import { Input } from '@/components/ui/input';

interface PaginationProps {
    results: PaginatedResponse<unknown>;
    sideLinkCount?: number; // maximum number of page buttons to each side of the current page.
    onPageChange?: (page: number) => void;
}

interface PaginationItem {
    id: string;
    value: number | null;
}

export const Pagination: React.FC<PaginationProps> = ({ results, sideLinkCount = 2, onPageChange }) => {

    const paginationData = results?.meta.pagination ?? undefined;

    const [pageNumbers, setPageNumbers] = useState<PaginationItem[]>([]);
    const [manualPageNumber, setManualPageNumber] = useState<number | ''>();

    useEffect(() => {
        buildPageNumbers();
    }, [results]);

    const buildPageNumbers = (): void => {
        const pageNumbers: PaginationItem[] = [];

        // Only bother with calculating the page numbers if there is more than one page.
        if (paginationData && paginationData?.total_pages > 1) {

            // We want to position the current page number so that it sits in the middle of the list.
            // We also always include the first and last pages.
            // e.g. 1 2 3 [4] 5 6 7
            // e.g. 1 2 [3] 4 5 6 7
            // e.g. 1 ... 5 6 [7] 8 9 ... 12

            // Which page numbers do we include?
            const currentPage = paginationData.current_page;
            const totalPages = paginationData.total_pages;

            const minPageNumber = Math.max(2, currentPage - sideLinkCount);
            const maxPageNumber = Math.min(currentPage + sideLinkCount, totalPages - 1);

            // Always include the first page.
            pageNumbers.push({ id: 'first', value: 1 });

            // Add an indicator if there are more pages past the left boundary.
            if (minPageNumber > 2) {
                pageNumbers.push({ id: `nl`, value: null });
            }

            // Add the page numbers to display.
            for (let i = minPageNumber; i <= maxPageNumber; i++) {
                pageNumbers.push({ id: `p${i}`, value: i });
            }

            // Add an indicator if there are more pages past the right boundary.
            if (maxPageNumber < totalPages - 1) {
                pageNumbers.push({ id: 'nr', value: null });
            }

            // Also include the last page.
            pageNumbers.push({ id: 'last', value: totalPages });
        }
        setPageNumbers(pageNumbers);
    };

    const isPageActive = (pageNumber: number): boolean => {
        return paginationData?.current_page === pageNumber;
    }

    const shouldShowInput = (): boolean => {
        return pageNumbers.some((pn) => pn.value === null);
    }

    const manualSubmitHandler = (e: SubmitEvent) => {
        e.preventDefault();
        if (onPageChange && manualPageNumber && !isNaN(manualPageNumber)) {
            onPageChange(manualPageNumber);
        }
        setManualPageNumber('');
        // do not use undefined!
        // https://react.dev/reference/react-dom/components/input#controlling-an-input-with-a-state-variable
    }

    return paginationData?.total_pages > 1 && (
        <nav className="flex mx-4 my-3 justify-center items-center gap-x-2">
            {shouldShowInput() && <form onSubmit={manualSubmitHandler}>
                <Input value={manualPageNumber} onChange={(v) => setManualPageNumber(v.target.value)} type="number"
                       min="0"
                       max={paginationData?.total_pages}
                       className="w-[6em] text-right"
                       placeholder="Page"/>
            </form>}
            {pageNumbers.map((pageNumber: PaginationItem) => (
                <React.Fragment key={pageNumber.id}>
                    {pageNumber.value ? <Button variant={isPageActive(pageNumber.value!) ? 'default' : 'ghost'}
                                          className="cursor-pointer"
                                                disabled={isPageActive(pageNumber.value!)}
                                                onClick={() => onPageChange && onPageChange(pageNumber.value!)}
                                                title={`Go to page ${pageNumber.value}`}>
                        {pageNumber.value}
                    </Button> : <Button variant="ghost" disabled>...</Button>}
                </React.Fragment>
            ))}
        </nav>
    )
}
