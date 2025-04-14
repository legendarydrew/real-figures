import { PaginatedResponse } from '@/types';
import { Button } from '@/components/ui/button';
import React, { useEffect, useState } from 'react';
import { Input } from '@/components/ui/input';

interface PaginationProps {
    results: PaginatedResponse<unknown>;
    linkCount?: number;
    onPageChange?: (page: number) => void;
}

export const Pagination: React.FC<PaginationProps> = ({ results, linkCount = 6, onPageChange }) => {

    const paginationData = results?.meta.pagination ?? undefined;

    const [pageNumbers, setPageNumbers] = useState<(number | null)[]>([]);
    const [manualPageNumber, setManualPageNumber] = useState<number | undefined>();

    useEffect(() => {
        buildPageNumbers();
    }, [results]);

    const buildPageNumbers = (): void => {
        const pageNumbers: (number | null)[] = [];
        if (paginationData && paginationData?.total_pages > 1) {

            // Which page numbers do we include?
            const minPageNumber = Math.max(2, paginationData.current_page - Math.ceil((linkCount + 1) / 2));
            const maxPageNumber = Math.min(paginationData.total_pages - 1, Math.ceil(paginationData.current_page + (linkCount + 1) / 2));
            // (This will take some figuring out!)

            // Always include the first page.
            pageNumbers.push(1);

            if (minPageNumber > 2) {
                pageNumbers.push(null);
            }
            for (let i = minPageNumber; i <= maxPageNumber; i++) {
                pageNumbers.push(i);
            }
            if (maxPageNumber < paginationData.total_pages - 1) {
                pageNumbers.push(null);
            }

            // Also include the last page.
            pageNumbers.push(paginationData.total_pages);
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
        setManualPageNumber(undefined);
    }

    return paginationData?.total_pages > 1 && (
        <nav className="flex mx-4 my-3 justify-center items-center gap-x-2">
            {shouldShowInput() && <form onSubmit={manualSubmitHandler}>
                <Input onChange={(v) => setManualPageNumber(v.target.value)} type="number" min="0"
                       max={paginationData?.total_pages}
                       className="w-[6em] text-right"
                       placeholder="Page"/>
            </form>}
            {pageNumbers.map((pageNumber: number) => (
                <React.Fragment key={pageNumber}>
                    {pageNumber ? (<Button variant={isPageActive(pageNumber) ? 'default' : 'ghost'}
                                           className="cursor-pointer"
                                           disabled={isPageActive(pageNumber)}
                                           onClick={() => onPageChange && onPageChange(pageNumber)}
                                           title={`Go to page ${pageNumber}`}>
                        {pageNumber}
                    </Button>) : (<Button variant="ghost" disabled>...</Button>)
                    }
                </React.Fragment>
            ))}
        </nav>
    )
}
