<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
    {
        title: 'Update User',
        href: `/user/${props.user.id}/edit`,
    },
];

const user = props.user as User;

const form = useForm({
    prefixname: user.prefixname,
    firstname: user.firstname,
    middlename: user.middlename,
    lastname: user.lastname,
    email: user.email,
});

const submit = () => {
    form.patch(route('users.update', props.user.id), {
        preserveScroll: true,
    });
};
</script>

<template>

    <Head :title="`Update ${user.full_name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col space-y-6">
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
                                    <SelectItem value="Mr">Mr</SelectItem>
                                    <SelectItem value="Mrs">Mrs</SelectItem>
                                    <SelectItem value="Ms">Ms</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.prefixname" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="firstname">First Name</Label>
                        <Input id="firstname" type="text" required autocomplete="given-name" v-model="form.firstname"
                            placeholder="First name" />
                        <InputError :message="form.errors.firstname" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="middlename">Middle Name</Label>
                        <Input id="middlename" type="text" autocomplete="additional-name" v-model="form.middlename"
                            placeholder="Middle name (optional)" />
                        <InputError :message="form.errors.middlename" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="lastname">Last Name</Label>
                        <Input id="lastname" type="text" required autocomplete="family-name" v-model="form.lastname"
                            placeholder="Last name" />
                        <InputError :message="form.errors.lastname" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" required autocomplete="username" v-model="form.email"
                            placeholder="Email address" />
                        <InputError :message="form.errors.email" />
                    </div>

                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="form.processing">Save</Button>

                    <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                        <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
