import { useRoute } from '@/composables/useRoute';
import { User } from '@/types';
import axios from 'axios';
import { ref } from 'vue';

export function useUserFetch() {
    const route = useRoute();

    const users = ref<User[]>([]);
    const hasMore = ref(false);
    const isLoading = ref(false);
    const isLoadingMore = ref(false);
    const page = ref(1);

    const fetchUsers = async (search: string = '', reset: boolean = true) => {
        if (reset) {
            isLoading.value = true;
            page.value = 1;
        } else {
            isLoadingMore.value = true;
        }

        try {
            const response = await axios.get(route('external.user-list'), {
                params: {
                    search: search.trim(),
                    page: page.value,
                },
            });

            if (reset) {
                users.value = response.data.users;
            } else {
                users.value = [...users.value, ...response.data.users];
            }

            hasMore.value = response.data.hasMore;
        } catch (error) {
            console.error('Error fetching user list:', error);
            throw error;
        } finally {
            isLoading.value = false;
            isLoadingMore.value = false;
        }
    };

    const loadMore = async (search: string = '') => {
        if (hasMore.value && !isLoadingMore.value) {
            page.value += 1;
            await fetchUsers(search, false);
        }
    };

    const resetUsers = () => {
        users.value = [];
        page.value = 1;
        hasMore.value = false;
        isLoading.value = false;
        isLoadingMore.value = false;
    };

    return {
        // State
        users,
        hasMore,
        isLoading,
        isLoadingMore,
        page,

        // Methods
        fetchUsers,
        loadMore,
        resetUsers,
    };
}
