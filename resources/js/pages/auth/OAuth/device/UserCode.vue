<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { AlertCircle, LoaderCircle, Shield, Smartphone } from 'lucide-vue-next';

interface Props {
    error?: string;
}

defineProps<Props>();

const form = useForm({
    user_code: '',
});

const submitCode = () => {
    form.post('/oauth/device/verify', {
        preserveScroll: true,
    });
};
</script>

<template>
    <AuthLayout title="Device Authorization" description="Enter the code displayed on your device">
        <Head title="Device Authorization" />

        <div class="space-y-6">
            <!-- Device Code Info -->
            <Card>
                <CardHeader class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                        <Smartphone class="h-6 w-6 text-primary" />
                    </div>
                    <CardTitle class="text-xl">Device Authorization</CardTitle>
                    <CardDescription> Enter the code displayed on your device to continue </CardDescription>
                </CardHeader>
            </Card>

            <!-- Error Message -->
            <div v-if="error" class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-950">
                <div class="flex items-start gap-2">
                    <AlertCircle class="mt-0.5 h-5 w-5 text-red-600 dark:text-red-400" />
                    <div class="text-sm">
                        <p class="font-medium text-red-800 dark:text-red-200">Error</p>
                        <p class="mt-1 text-red-700 dark:text-red-300">{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Errors -->
            <div v-if="form.errors.user_code" class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-950">
                <div class="flex items-start gap-2">
                    <AlertCircle class="mt-0.5 h-5 w-5 text-red-600 dark:text-red-400" />
                    <div class="text-sm">
                        <p class="font-medium text-red-800 dark:text-red-200">Invalid Code</p>
                        <p class="mt-1 text-red-700 dark:text-red-300">{{ form.errors.user_code }}</p>
                    </div>
                </div>
            </div>

            <!-- Code Input Form -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg">Enter Device Code</CardTitle>
                    <CardDescription> Please enter the code shown on your device screen </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submitCode" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="user_code">Device Code</Label>
                            <Input
                                id="user_code"
                                v-model="form.user_code"
                                type="text"
                                placeholder="Enter your device code"
                                class="text-center font-mono text-lg tracking-wider uppercase"
                                maxlength="8"
                                required
                                :disabled="form.processing"
                            />
                            <p class="text-xs text-muted-foreground">Code should be 8 characters long (e.g., ABCD-1234)</p>
                        </div>

                        <Button type="submit" class="w-full" :disabled="form.processing || !form.user_code">
                            <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                            Verify Code
                        </Button>
                    </form>
                </CardContent>
            </Card>

            <!-- Instructions -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg">Instructions</CardTitle>
                </CardHeader>
                <CardContent>
                    <ol class="space-y-2 text-sm text-muted-foreground">
                        <li class="flex gap-2">
                            <span class="font-medium">1.</span>
                            <span>Look for the device code displayed on your device or application</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="font-medium">2.</span>
                            <span>Enter the code exactly as shown (case insensitive)</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="font-medium">3.</span>
                            <span>Click "Verify Code" to authorize the device</span>
                        </li>
                    </ol>
                </CardContent>
            </Card>

            <!-- Security Notice -->
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-950">
                <div class="flex items-start gap-2">
                    <Shield class="mt-0.5 h-5 w-5 text-amber-600 dark:text-amber-400" />
                    <div class="text-sm">
                        <p class="font-medium text-amber-800 dark:text-amber-200">Security Notice</p>
                        <p class="mt-1 text-amber-700 dark:text-amber-300">
                            Only enter codes from devices you trust. This will authorize the device to access your account.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
