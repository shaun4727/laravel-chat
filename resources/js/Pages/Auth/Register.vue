<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="grid place-items-center h-screen bg-[#F9FAFB">
            <div
                class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
            >
                <div class="max-w-md w-full space-y-8">
                    <div>
                        <img
                            class="mx-auto h-12 w-auto"
                            src="./assets/lws-logo-light.svg"
                            alt="Learn with sumit"
                        />
                        <h2
                            class="mt-6 text-center text-3xl font-extrabold text-gray-900"
                        >
                            Create your account
                        </h2>
                    </div>
                    <form class="mt-8 space-y-6" @submit.prevent="submit">
                        <input type="hidden" name="remember" value="true" />
                        <div class="rounded-md shadow-sm -space-y-px">
                            <div>
                                <label for="name" class="sr-only"
                                    >Full Name</label
                                >
                                <input
                                    id="name"
                                    name="Name"
                                    type="Name"
                                    autocomplete="Name"
                                    v-model="form.name"
                                    required
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-violet-500 focus:border-violet-500 focus:z-10 sm:text-sm"
                                    placeholder="Name"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <label for="email-address" class="sr-only"
                                    >Email address</label
                                >
                                <input
                                    id="email-address"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    v-model="form.email"
                                    required
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-violet-500 focus:border-violet-500 focus:z-10 sm:text-sm"
                                    placeholder="Email address"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div>
                                <label for="password" class="sr-only"
                                    >Password</label
                                >
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="current-password"
                                    v-model="form.password"
                                    required
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-violet-500 focus:border-violet-500 focus:z-10 sm:text-sm"
                                    placeholder="Password"
                                />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div>
                                <label for="confirmPassword" class="sr-only"
                                    >Confirm Password</label
                                >
                                <input
                                    id="confirmPassword"
                                    name="confirmPassword"
                                    type="confirmPassword"
                                    autocomplete="current-confirmPassword"
                                    v-model="form.password_confirmation"
                                    required
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-violet-500 focus:border-violet-500 focus:z-10 sm:text-sm"
                                    placeholder="confirmPassword"
                                />
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <Link
                                    :href="route('login')"
                                    class="ml-2 block text-sm text-gray-900"
                                >
                                    Already registered?
                                </Link>
                            </div>
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500"
                            >
                                <span
                                    class="absolute left-0 inset-y-0 flex items-center pl-3"
                                >
                                </span>
                                Sign up
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</template>
