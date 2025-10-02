import axios from 'axios';

export const fetchNotifications = async (page: number, per_page: number, show_unread: boolean) => {
    const response = await axios.get('/settings/notifications/dropdown', {
        params: { page, per_page, show_unread },
    });
    return response.data;
};

import { User } from '@/types';
import { ref } from 'vue';

const userOnline = ref<User[]>([]);

export function useOnlineUsers() {
    return userOnline;
}
