<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
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

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage();
const user = page.props.auth.user as User;

const form = useForm({
    prefixname: user.prefixname,
    firstname: user.firstname,
    middlename: user.middlename,
    lastname: user.lastname,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />
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
                            <Input id="firstname" type="text" required autocomplete="given-name"
                                v-model="form.firstname" placeholder="First name" />
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

                        <div class="grid gap-2">
                            <Label for="password">Password</Label>
                            <Input id="password" type="password" required autocomplete="new-password"
                                v-model="form.password" placeholder="Password" />
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password_confirmation">Confirm password</Label>
                            <Input id="password_confirmation" type="password" required autocomplete="new-password"
                                v-model="form.password_confirmation" placeholder="Confirm password" />
                            <InputError :message="form.errors.password_confirmation" />
                        </div>
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at" class="mt-4">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link :href="route('verification.send')" method="post" as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">
                            Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
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

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
