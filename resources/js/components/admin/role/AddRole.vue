<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useForm, usePage } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import RoleForm from './RoleForm.vue';

const page = usePage();

const form = useForm({
    name: '',
    display_name: '',
    description: '',
});

const dialog = ref(false);

const submit = () => {
    form.post(route('admin.roles.store'), {
        onSuccess: () => {
            form.reset();
            dialog.value = false;
            toast.success('Role created successfully', {
                description: page.props.flash.success || 'The role has been created successfully.',
                closeButton: true,
            });
        },
        onError: (error) => {
            toast.error('Failed to create role', {
                description: error.message || 'An error occurred while creating the role.',
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
                Add Role
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle> Add New Role </DialogTitle>
                <DialogDescription> Use the form below to create a new role. </DialogDescription>
            </DialogHeader>
            <RoleForm
                :form="{ name: form.name, display_name: form.display_name, description: form.description }"
                :errors="form.errors"
                :processing="form.processing"
                submit-label="Create Role"
                @submit="submit"
                @update:form="
                    (newForm) => {
                        form.name = newForm.name;
                        form.display_name = newForm.display_name;
                        form.description = newForm.description;
                    }
                "
            />
        </DialogContent>
    </Dialog>
</template>
