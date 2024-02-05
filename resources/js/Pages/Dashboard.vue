<script setup>
import Nav from '@/Components/Chat/Nav.vue';
import Sidebar from '@/Components/Chat/Sidebar.vue';
import Conversation from '@/Components/Chat/Conversation.vue';
import {computed,ref,onMounted,defineExpose } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({users:Object,auth:Object,conversation:Object,messages:Object, singleConversation: Object})

// update active user
const all_convs = ref([]);



// update conversation
const conv = ref({});
const newMsg = ref({});
const updateConversation = (conversation)=>{
    conv.value = conversation;
}

onMounted(()=>{

    // window.Echo.channel('MyChannel')
    // .listenForWhisper('typing', (e) => {
    //         console.log(e.name);
    //     })
    // .listen('MyWebsocketEvent',(e)=>{
    //     console.log(e);
    // });

    // window.Echo.private(`chats.${props.auth.user.id}`)
    window.Echo.private(`chats`)

        .listen('SendMessageEvent',(event)=>{
            newMsg.value = event.message;


        }).listenForWhisper('typing',(e)=>{
            console.log(e);
        })

        window.Echo.join("MyChannel").listenForWhisper("test",(e)=>{
            console.log(e);
        })

})


// update sidebar msg
const last_sidebar_msg = ref({});
const updateSidebarMsg = (value)=>{
    last_sidebar_msg.value = value;
}



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
                    <Sidebar :USERS="users" :AUTH="auth" :CONVERSATION="conversation"
                    :NEWMSG="newMsg" :LASTMSG="last_sidebar_msg"
                        @update-conversation="updateConversation"
                    />





                    <!-- Conversation -->

                    <Conversation :CONVERSATION="singleConversation" :AUTH="auth" :MESSAGES="messages"
                        @update-sidebar-msg="updateSidebarMsg"
                        :NEWMSG="newMsg"
                    />












                </div>
            </div>
        </div>
</template>
<style src="./Auth/login.css" scoped>
</style>
