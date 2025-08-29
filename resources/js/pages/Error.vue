<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useRoute } from '@/composables/useRoute';
import { Head, Link } from '@inertiajs/vue3';
import { CopySlash, ServerCrash, ShieldOff } from 'lucide-vue-next';
import { computed } from 'vue';
const route = useRoute();

const props = defineProps({ status: Number });

const errorInfo = computed(
    () =>
        ({
            503: {
                title: '503: Service Unavailable',
                description: 'Sorry, we are doing some maintenance. Please check back soon.',
                icon: ServerCrash,
            },
            500: {
                title: '500: Server Error',
                description: 'Whoops, something went wrong on our servers.',
                icon: ServerCrash,
            },
            404: {
                title: '404: Page Not Found',
                description: 'Sorry, the page you are looking for could not be found.',
                icon: CopySlash,
            },
            403: {
                title: '403: Forbidden',
                description: 'Sorry, you are forbidden from accessing this page.',
                icon: ShieldOff,
            },
        })[props.status ?? 500],
);
</script>

<template>
    <Head :title="errorInfo?.title" />
    <div class="flex h-screen flex-col items-center justify-center gap-4 rounded-xl p-4 text-center">
        <component :is="errorInfo?.icon" class="text-primary-500 h-20 w-20" />
        <h1 class="text-2xl font-bold italic">{{ errorInfo?.title }}</h1>
        <p class="text-lg font-semibold italic">{{ errorInfo?.description }}</p>
        <Link :href="route('dashboard')">
            <Button class="border-2 border-white bg-black text-white hover:border-black hover:bg-white hover:text-black">Back to Home</Button>
        </Link>
    </div>
</template>
