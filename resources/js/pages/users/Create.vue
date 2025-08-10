<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';

const form = useForm({
    prefixname: '',
    firstname: '',
    middlename: '',
    lastname: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('users.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
    {
        title: 'User',
        href: `/user/create`,
    },
];
</script>

<template>

    <Head title="Create user" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="prefixname">Prefix Name</Label>
                    <Select v-model="form.prefixname">
                        <SelectTrigger>
                            <SelectValue placeholder="Select prefix" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Prefix</SelectLabel>
                                <SelectItem value="Mr">
                                    Mr
                                </SelectItem>
                                <SelectItem value="Mrs">
                                    Mrs
                                </SelectItem>
                                <SelectItem value="Ms">
                                    Ms
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.prefixname" />
                </div>

                <div class="grid gap-2">
                    <Label for="firstname">First Name</Label>
                    <Input id="firstname" type="text" required :tabindex="2" autocomplete="given-name"
                        v-model="form.firstname" placeholder="First name" />
                    <InputError :message="form.errors.firstname" />
                </div>

                <div class="grid gap-2">
                    <Label for="middlename">Middle Name</Label>
                    <Input id="middlename" type="text" :tabindex="3" autocomplete="additional-name"
                        v-model="form.middlename" placeholder="Middle name (optional)" />
                    <InputError :message="form.errors.middlename" />
                </div>

                <div class="grid gap-2">
                    <Label for="lastname">Last Name</Label>
                    <Input id="lastname" type="text" required :tabindex="4" autocomplete="family-name"
                        v-model="form.lastname" placeholder="Last name" />
                    <InputError :message="form.errors.lastname" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input id="email" type="email" required :tabindex="5" autocomplete="email" v-model="form.email"
                        placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" required :tabindex="6" autocomplete="new-password"
                        v-model="form.password" placeholder="Password" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input id="password_confirmation" type="password" required :tabindex="7" autocomplete="new-password"
                        v-model="form.password_confirmation" placeholder="Confirm password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" :tabindex="8" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create user
                </Button>
            </div>
        </form>
    </AppLayout>

</template>
