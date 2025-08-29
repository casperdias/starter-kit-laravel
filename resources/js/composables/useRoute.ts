
import { inject } from "vue";
import {route as ziggyRoute} from "ziggy-js";
export const route = inject<typeof ziggyRoute>("route")!;
