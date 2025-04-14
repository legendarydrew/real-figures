import { PaginatedResponse } from '@/types';
import { Button } from '@/components/ui/button';
import React, { useEffect, useState } from 'react';
import { Input } from '@/components/ui/input';

interface PaginationProps {
    results: PaginatedResponse<unknown>;
    sideLinkCount?: number; // maximum number of page buttons to each side of the current page.
    onPageChange?: (page: number) => void;
}

export const Pagination: React.FC<PaginationProps> = ({ results, sideLinkCount = 2, onPageChange }) => {

    const paginationData = results?.meta.pagination ?? undefined;

    const [pageNumbers, setPageNumbers] = useState<(number | null)[]>([]);
    const [manualPageNumber, setManualPageNumber] = useState<number | ''>();

    useEffect(() => {
        buildPageNumbers();
    }, [results]);

    const buildPageNumbers = (): void => {
        const pageNumbers: (number | null)[] = [];

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

            let minPageNumber = Math.max(2, currentPage - sideLinkCount);
            let maxPageNumber = Math.min(currentPage + sideLinkCount, totalPages - 1);

            minPageNumber = Math.min(minPageNumber, totalPages - sideLinkCount * 2 - 1);
            maxPageNumber = Math.max(maxPageNumber, 1 + sideLinkCount * 2);

            // Always include the first page.
            pageNumbers.push(1);

            // Add an indicator if there are more pages past the left boundary.
            if (minPageNumber > 2) {
                pageNumbers.push(null);
            }

            // Add the page numbers to display.
            for (let i = minPageNumber; i <= maxPageNumber; i++) {
                pageNumbers.push(i);
            }

            // Add an indicator if there are more pages past the right boundary.
            if (maxPageNumber < totalPages - 1) {
                pageNumbers.push(null);
            }

            // Also include the last page.
            pageNumbers.push(totalPages);
        }
        setPageNumbers(pageNumbers);
    };

    const isPageActive = (pageNumber: number): boolean => {
        return paginationData?.current_page === pageNumber;
    }

    const shouldShowInput = (): boolean => {
        return pageNumbers.includes(null);
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
            {pageNumbers.map((pageNumber: number, index) => (
                <React.Fragment key={index}>
                    {pageNumber ? <Button variant={isPageActive(pageNumber) ? 'default' : 'ghost'}
                                          className="cursor-pointer"
                                          disabled={isPageActive(pageNumber)}
                                          onClick={() => onPageChange && onPageChange(pageNumber)}
                                          title={`Go to page ${pageNumber}`}>
                        {pageNumber}
                    </Button> : <Button variant="ghost" disabled>...</Button>}
                </React.Fragment>
            ))}
        </nav>
    )
}
