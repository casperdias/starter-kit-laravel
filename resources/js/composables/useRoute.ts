import { inject } from 'vue';
import { route as ziggyRoute } from 'ziggy-js';

export function useRoute() {
    return inject<typeof ziggyRoute>('route')!;
}
