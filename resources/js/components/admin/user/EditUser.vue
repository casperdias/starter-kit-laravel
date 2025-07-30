<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Wrench } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import UserForm from './UserForm.vue';

const page = usePage();

const props = defineProps<{
    id: number;
}>();

const dialog = ref(false);

const form = useForm({
    name: '',
    email: '',
});

watch(dialog, (isOpen) => {
    if (isOpen) {
        axios
            .get(route('admin.users.edit', props.id))
            .then((response) => {
                form.name = response.data.name;
                form.email = response.data.email;
            })
            .catch((error) => {
                toast.error('Failed to load user details', {
                    description: error.message || 'An error occurred while fetching the user details.',
                    closeButton: true,
                });
            });
    } else {
        form.reset();
    }
});

const submit = () => {
    form.put(route('admin.users.update', props.id), {
        onSuccess: () => {
            form.reset();
            dialog.value = false;
            toast.success('User updated successfully', {
                description: page.props.flash.success || 'The user has been updated successfully.',
                closeButton: true,
            });
        },
        onError: (error) => {
            toast.error('Failed to update user', {
                description: error.message || 'An error occurred while updating the user.',
                closeButton: true,
            });
        },
    });
};
</script>

<template>
    <Dialog v-model:open="dialog">
        <DialogTrigger as-child>
            <Button>
                <Wrench />
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Edit User</DialogTitle>
                <DialogDescription> Use the form below to edit the user. </DialogDescription>
            </DialogHeader>
            <UserForm
                :form="{ name: form.name, email: form.email }"
                :errors="form.errors"
                :processing="form.processing"
                submit-label="Update User"
                @submit="submit"
                @update:form="
                    (newForm) => {
                        form.name = newForm.name;
                        form.email = newForm.email;
                    }
                "
            />
        </DialogContent>
    </Dialog>
</template>
