<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useForm, usePage } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { ref } from 'vue';

const page = usePage();

const form = useForm({
    name: '',
    redirect: [],
    confidential: false,
    device_flow: true,
});

const dialog = ref(false);
</script>

<template>
    <Dialog v-model:open="dialog">
        <DialogTrigger as-child>
            <Button class="w-full cursor-pointer">
                <Plus />
                Add Client
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle> Add New Client </DialogTitle>
                <DialogDescription> Use the form below to create a new client. </DialogDescription>
            </DialogHeader>
        </DialogContent>
    </Dialog>
</template>
