<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Globe, LoaderCircle, Shield, Smartphone, User } from 'lucide-vue-next';

interface Props {
    request: Record<string, string | number | boolean>;
    authToken: string;
    client: {
        id: string;
        name: string;
        redirect_uris: string[];
    };
    user: {
        id: number;
        name: string;
        email: string;
    };
    scopes: Array<{
        id: string;
        description: string;
    }>;
}

const props = defineProps<Props>();

const approveForm = useForm({
    state: props.request.state,
    client_id: props.request.client_id,
    auth_token: props.authToken,
});

const denyForm = useForm({
    state: props.request.state,
    client_id: props.request.client_id,
    auth_token: props.authToken,
});

const approve = () => {
    approveForm.post('/oauth/device/authorize', {
        preserveScroll: true,
    });
};

const deny = () => {
    denyForm.delete('/oauth/device/authorize', {
        preserveScroll: true,
    });
};
</script>

<template>
    <AuthLayout title="Authorize Device" :description="`${client.name} device is requesting access to your account`">
        <Head title="Authorize Device" />

        <div class="space-y-6">
            <!-- Application Info -->
            <Card>
                <CardHeader class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                        <Smartphone class="h-6 w-6 text-primary" />
                    </div>
                    <CardTitle class="text-xl">{{ client.name }}</CardTitle>
                    <CardDescription> This device is requesting access to your account </CardDescription>
                </CardHeader>
            </Card>

            <!-- User Info -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2 text-lg">
                        <User class="h-5 w-5" />
                        Account Information
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Name:</span>
                            <span class="text-sm font-medium">{{ user.name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Email:</span>
                            <span class="text-sm font-medium">{{ user.email }}</span>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Device Info -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2 text-lg">
                        <Smartphone class="h-5 w-5" />
                        Device Information
                    </CardTitle>
                    <CardDescription> The device will be authorized to access your account with the permissions below </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Application:</span>
                            <span class="text-sm font-medium">{{ client.name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Client ID:</span>
                            <span class="font-mono text-sm">{{ client.id }}</span>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Scopes/Permissions -->
            <Card v-if="scopes && scopes.length > 0">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2 text-lg">
                        <Globe class="h-5 w-5" />
                        Requested Permissions
                    </CardTitle>
                    <CardDescription> This device is requesting the following permissions: </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-2">
                        <Badge v-for="scope in scopes" :key="scope.id" variant="secondary" class="mr-2 mb-2">
                            {{ scope.description }}
                        </Badge>
                    </div>
                </CardContent>
            </Card>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 sm:flex-row">
                <Button @click="approve" class="flex-1" :disabled="approveForm.processing || denyForm.processing">
                    <LoaderCircle v-if="approveForm.processing" class="mr-2 h-4 w-4 animate-spin" />
                    Authorize Device
                </Button>

                <Button @click="deny" variant="outline" class="flex-1" :disabled="approveForm.processing || denyForm.processing">
                    <LoaderCircle v-if="denyForm.processing" class="mr-2 h-4 w-4 animate-spin" />
                    Deny Access
                </Button>
            </div>

            <!-- Device Flow Info -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg">Device Authorization Flow</CardTitle>
                    <CardDescription> This is a secure device authorization request </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-2 text-sm text-muted-foreground">
                        <p>• Your device has generated a unique authorization request</p>
                        <p>• Authorizing will grant the device access with the requested permissions</p>
                        <p>• You can revoke this access at any time from your account settings</p>
                        <p>• The device will not have access to your password or other sensitive information</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Security Notice -->
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-950">
                <div class="flex items-start gap-2">
                    <Shield class="mt-0.5 h-5 w-5 text-amber-600 dark:text-amber-400" />
                    <div class="text-sm">
                        <p class="font-medium text-amber-800 dark:text-amber-200">Security Notice</p>
                        <p class="mt-1 text-amber-700 dark:text-amber-300">
                            Only authorize devices you own and trust. This will give the device access to your account with the requested permissions.
                            You can always revoke access later from your account settings.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
