<script setup lang="ts">
import ChatBox from '@/components/ChatBox.vue';
import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, Message, User } from '@/types';
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
    tagged_id: 0,
});

// Send Message
const sendMessage = () => {
    const socketId = echo().socketId();
    axios
        .post(
            route('chat.send'),
            {
                message: form.message,
                tagged_id: form.tagged_id,
            },
            {
                headers: {
                    'X-Socket-Id': socketId,
                },
            },
        )
        .then((response) => {
            const now = new Date();
            const formattedDate = new Intl.DateTimeFormat('en-GB', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZoneName: 'short',
            })
                .format(now)
                .replace(',', '');
            messages.value.push({
                id: response.data.id,
                user: page.props.auth.user,
                message: form.message,
                taggedUser: taggedUser.value,
                created_at: formattedDate,
            });
            form.message = '';
        });
};

const taggedUser = ref();
const tagOpen = ref(false);
const users = ref<User[]>([]);

onMounted(() => {
    axios.get(route('chat.get')).then((response) => {
        messages.value = response.data;
    });
    axios.get(route('chat-admin.tagable')).then((response) => {
        users.value = response.data;
    });
});
</script>

<template>
    <Head title="Chat Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <div class="flex h-96 flex-col space-y-4">
                <ChatBox :messages="messages" :user="page.props.auth.user" />
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <Popover v-model:open="tagOpen">
                        <PopoverTrigger as-child>
                            <Button variant="outline">
                                <template v-if="taggedUser">
                                    {{ taggedUser.name }}
                                </template>
                                <template v-else> @ </template>
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="p-0" align="start">
                            <Command>
                                <CommandInput placeholder="Search User" />
                                <CommandList>
                                    <CommandEmpty>No users found</CommandEmpty>
                                    <CommandGroup>
                                        <CommandItem
                                            v-for="user in users"
                                            :key="user.id"
                                            :value="user.id"
                                            @select="
                                                () => {
                                                    taggedUser = user;
                                                    tagOpen = false;
                                                    form.tagged_id = user.id;
                                                }
                                            "
                                            class="p-2"
                                        >
                                            <div class="flex flex-col text-xs">
                                                <p class="font-semibold">{{ user.name }}</p>
                                                <p>{{ user.email }}</p>
                                            </div>
                                        </CommandItem>
                                    </CommandGroup>
                                </CommandList>
                            </Command>
                        </PopoverContent>
                    </Popover>
                    <Input
                        v-model="form.message"
                        type="text"
                        placeholder="Type your message..."
                        class="flex-1 rounded border px-2 py-1"
                        autocomplete="off"
                    />
                    <Button type="submit" :disabled="!form.message.trim() || !form.tagged_id">
                        <Send />
                    </Button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
