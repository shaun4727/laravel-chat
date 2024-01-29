<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
            <nav
                class="border-general sticky top-0 z-40 border-b bg-violet-700 transition-colors"
            >
                <div class="max-w-7xl mx-auto">
                    <div class="flex justify-between h-16 items-center">
                        <img class="h-10" src="./../../Pages/Auth/assets/lws-logo-dark.svg" />
                        <ul>
                            <li class="text-white">
                                <Link :href="route('logout')" method="post">Logout</Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
</template>
<style src="./../../Pages/Auth/login.css" scoped>

</style>
