import { router } from '@inertiajs/vue3';

/**
 * Change the current page of a paginated route.
 *
 * @param {string} route - The route to navigate to.
 * @param {number} page - The page number to navigate to.
 * @param {string} [pageParam='page'] - The query parameter name for the page.
 */
export function changePage(route: string, page: number | string, pageParam: string = 'page', only: string[]) {
    const currentParams = new URLSearchParams(window.location.search);
    // Remove the current page parameter if it exists
    currentParams.delete(pageParam);

    router.get(
        route,
        {
            [pageParam]: page,
            ...Object.fromEntries(currentParams.entries()),
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: only,
        },
    );
}
