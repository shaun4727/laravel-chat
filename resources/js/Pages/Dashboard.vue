<script setup>
import Nav from '@/Components/Chat/Nav.vue';
import Sidebar from '@/Components/Chat/Sidebar.vue';
import Conversation from '@/Components/Chat/Conversation.vue';

defineProps({users:Object,auth:Object,conversation:Object})

</script>

<template>
        <div>
            <!-- Navigation -->
            <Nav />
            <div class="max-w-7xl mx-auto -mt-1">
                <div
                    class="min-w-full border rounded flex lg:grid lg:grid-cols-3"
                >
                    <!-- Sidebar -->
                    <Sidebar :USERS="users" :AUTH="auth" :CONVERSATION="conversation"/>





                    <!-- Conversation -->

                    <Conversation />












                </div>
            </div>
        </div>
</template>
<style src="./Auth/login.css" scoped>
</style>
