import { inject } from 'vue';
import { route as ziggyRoute } from 'ziggy-js';

// Export a function named 'route'
export function useRoute() {
    return inject<typeof ziggyRoute>('route')!;
}
