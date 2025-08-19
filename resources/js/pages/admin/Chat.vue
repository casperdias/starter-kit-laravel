<script setup lang="ts">
import ChatBox from '@/components/ChatBox.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, Message } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { echo, useEcho } from '@laravel/echo-vue';
import axios from 'axios';
import { Send } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Chat Admin',
        href: '/chat-admin',
    },
];
const page = usePage<AppPageProps>();

const messages = ref<Message[]>([]);
useEcho<Message>('admin-chat', 'ChatSent', (message) => {
    messages.value.push(message);
});

const form = useForm({
    message: '',
});

// Send Message
const sendMessage = () => {
    const socketId = echo().socketId();
    axios
        .post(
            route('chat.send'),
            {
                message: form.message,
            },
            {
                headers: {
                    'X-Socket-Id': socketId,
                },
            },
        )
        .then((response) => {
            messages.value.push({
                id: response.data.id,
                user: page.props.auth.user,
                message: form.message,
                taggedUser: null,
                created_at: new Date().toISOString(),
            });
            form.message = '';
        });
};

onMounted(() => {
    axios.get(route('chat.get')).then((response) => {
        messages.value = response.data;
    });
});
</script>

<template>
    <Head title="Chat Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <div class="flex h-96 flex-col space-y-4">
                <ChatBox :messages="messages" />
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <Input
                        v-model="form.message"
                        type="text"
                        placeholder="Type your message..."
                        class="flex-1 rounded border px-2 py-1"
                        autocomplete="off"
                    />
                    <Button type="submit" :disabled="!form.message.trim()">
                        <Send />
                    </Button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
