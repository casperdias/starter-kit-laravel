<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useForm, usePage } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { UserForm } from '.';

const page = usePage();

const dialog = ref(false);

const form = useForm({
    name: '',
    email: '',
});

const submit = () => {
    form.post(route('admin.users.store'), {
        onSuccess: () => {
            form.reset();
            dialog.value = false;
            toast.success('User created successfully', {
                description: page.props.flash.success || 'The user has been created successfully.',
                closeButton: true,
            });
        },
        onError: (error) => {
            toast.error('Failed to create user', {
                description: error.message || 'An error occurred while creating the user.',
                closeButton: true,
            });
        },
    });
};
</script>

<template>
    <Dialog v-model:open="dialog">
        <DialogTrigger as-child>
            <Button class="w-full cursor-pointer">
                <Plus />
                Add User
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle> Add New User </DialogTitle>
                <DialogDescription> Use the form below to create a new user. </DialogDescription>
            </DialogHeader>
            <UserForm
                :form="{ name: form.name, email: form.email }"
                :errors="form.errors"
                :processing="form.processing"
                submit-label="Create User"
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
