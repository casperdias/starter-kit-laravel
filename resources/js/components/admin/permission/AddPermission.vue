<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useForm, usePage } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { PermissionForm } from '.';
import { route } from '@/composables/useRoute';

const page = usePage();

const form = useForm({
    name: '',
    display_name: '',
    description: '',
});

const dialog = ref(false);

const submit = () => {
    form.post(route('admin.permissions.store'), {
        onSuccess: () => {
            form.reset();
            dialog.value = false;
            toast.success('Permission created successfully', {
                description: page.props.flash.success || 'The permission has been created successfully.',
                closeButton: true,
            });
        },
        onError: (error) => {
            toast.error('Failed to create permission', {
                description: error.message || 'An error occurred while creating the permission.',
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
                Add Permission
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle> Add New Permission </DialogTitle>
                <DialogDescription> Use the form below to create a new permission. </DialogDescription>
            </DialogHeader>
            <PermissionForm
                :form="{ name: form.name, display_name: form.display_name, description: form.description }"
                :errors="form.errors"
                :processing="form.processing"
                submit-label="Create Permission"
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
